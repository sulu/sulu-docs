Caching with Varnish
====================

Varnish is a HTTP `caching proxy server`_ which can be used to radically
improve the response time of your website.

Sulu is bundled with a "soft" caching proxy, the `Symfony HttpCache`_, however
this is not as fast or powerful as Varnish.

This tutorial will walk you through the process of setting up Varnish on 
your own server and configuring it to work with Sulu.

This tutorial assumes that:

- You are using the Apache2 webserver
- You are running Ubuntu or Debian

The steps should apply equally to other variants.

Install Varnish
---------------

On Ubuntu/Debian install Varnish as follows:

.. code-block:: bash

    apt-get install varnish

This should install and start the Varnish daemon.

Server Configurations
---------------------

Web Server
~~~~~~~~~~

For a caching server to serve pages to your users, it will need to "pretend" to
be the webserver. Webservers listen to requests on port 80, we need to change
the port of the webserver to something else, and make Varnish listen on port 80
instead.

You need to configure your webserver to listen on a different port. Change the 
``Listen`` directive to ``Listen 8090``:

.. code-block:: apache

    # /etc/apache2/ports.conf
    # ...
    Listen 8090

And change any and all virtual hosts to now listen on ``8090``:

.. code-block:: apache

    # /etc/apache2/conf.d/sites-available/sulu.conf
    # ...
    <VirtualHost \*:8090>
        # ...
    </VirtualHost>

Now you will need to configure varnish.

Varnish
~~~~~~~

If Varnish is not listening on port 80 it isn't going to do anything useful, so
check that it is listening on the correct port:

.. code-block:: bash

    $ ps ax | grep varnish
    6585 ?        SLs    0:00 varnishd -f /home/daniel/.varnish/sulu.vcl -s malloc,1G -T 127.0.0.1:2000 -a 0.0.0.0:8083
    6609 ?        Sl     0:07 varnishd -f /home/daniel/.varnish/sulu.vcl -s malloc,1G -T 127.0.0.1:2000 -a 0.0.0.0:8083

The ``-a`` option indicates where Varnish is listening - it is listening on port ``8083``, which is incorrect.

Under Debian/Ubunto we can change the initialization script:

.. code-block:: bash

    # /etc/default/varnish

    # ...
    DAEMON_OPTS="-a :80 \      
                 -T localhost:6082 \             
                 -f /etc/varnish/default.vcl \   
                 -S /etc/varnish/secret \        
                 -s malloc,256m 

Now restart the daemon:

.. code-block:: bash

    /etc/init.d/varnishd restart

Varnish Configuration
---------------------

The following will add full caching support for Sulu:

.. code-block:: varnish4

    # /etc/varnish/default.vcl
    vcl 4.0;

    acl invalidators {
        "localhost";
    }

    backend default {
        .host = "127.0.0.1";
        .port = "8090";
    }

    sub vcl_recv {
        if (req.method == "PURGE") {
            if (!client.ip ~ invalidators) {
                return (synth(405, "Not allowed"));
            }
            return (purge);
        }

        if (req.method == "BAN") {
            if (!client.ip ~ invalidators) {
                return (synth(405, "Not allowed"));
            }


            if (req.http.x-cache-tags) {
                ban("obj.http.x-host ~ " + req.http.x-host
                    + " && obj.http.x-url ~ " + req.http.x-url
                    + " && obj.http.content-type ~ " + req.http.x-content-type
                    + " && obj.http.x-cache-tags ~ " + req.http.x-cache-tags
                );
            } else {
                ban("obj.http.x-host ~ " + req.http.x-host
                    + " && obj.http.x-url ~ " + req.http.x-url
                    + " && obj.http.content-type ~ " + req.http.x-content-type
                );
            }

            return (synth(200, "Banned"));
        }
    }

    sub vcl_backend_response {
        # Set ban-lurker friendly custom headers
        set beresp.http.x-url = bereq.url;
        set beresp.http.x-host = bereq.http.host;
    }

    sub vcl_deliver {

        if (!resp.http.x-cache-debug) {
            unset resp.http.x-url;
            unset resp.http.x-host;
        }

        if (obj.hits > 0) {
            set resp.http.X-Cache = "HIT";
        } else {
            set resp.http.X-Cache = "MISS";
        }
    }

Restart Varnish:

.. code-block:: bash

    $ /etc/init.d/varnish restart

And now have a look at the headers on your website:

.. code-block:: bash

    $ curl -I mywebsite.com
    HTTP/1.1 200 OK
    # ...
    Via: 1.1 varnish
    # ...

If you see the above ``Via`` header, then all is good and your are ready to go forward.

Configuring Sulu Invalidation
-----------------------------

So. Now your pages will be cached, but they will not be invalidated - this means that
content will only be updated when the cache expires. We need to configure invalidation.

First tell the ``FOSHttpCacheBundle`` about the new varnish server:

.. code-block:: yaml

    # app/config/config.yml
    # ...
    fos_http_cache: 
        proxy_client: 
            varnish: 
                servers: localhost:8090
                base_url: <your domain name>

Replace ``<your domain name>`` with the corresponding value, e.g. ``www.mydomain.dom``.

Now ensure that the ``tags``` invalidator is enabled and change the ``max_age`` and ``max_shared_age``
to sufficiently high values (we effectively want to cache things for a very long time, because we
will manually tell Varnish to refresh them).

.. code-block:: yaml

    sulu_http_cache:
        structure_cache_handlers:
            aggregate:
                handlers: [ standard, path, tags ]
            standard:
                max_age: 604800 # 1 week
                shared_max_age: 604800 # 1 week

Now have another look at the headers from your website:

.. code-block:: bash

    $ curl -I mywebsite.dom
    HTTP/1.1 200 OK
    Cache-Control: max-age=400, public, s-maxage=1000
    X-Cache-Debug: 1
    X-Reverse-Proxy-TTL: 2400
    X-Cache-Tags: structure-4dfd5a3a-822e-4f21-b90e-b2ae93907dc1,structure-685c5542-0051-4ce9-a22e-9c4ca438447d,structure-aff47495-de8a-4b34-bfd3-c65e996959b0
    X-Debug-Token: 7ebdde
    X-Debug-Token-Link: /_profiler/7ebdde
    Content-Type: text/html; charset=UTF-8
    x-url: /de
    x-host: sulu.lo:8090
    X-Varnish: 65547 32782
    Age: 1
    Via: 1.1 varnish-v4
    X-Cache: HIT
    Content-Length: 10453
    Connection: keep-alive
    Proxy-Connection: keep-alive

.. _Caching Proxy Server: https://en.wikipedia.org/wiki/Proxy_server
.. _Symfony HttpCache: http://symfony.com/doc/current/book/http_cache.html
