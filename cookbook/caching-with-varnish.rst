Caching with Varnish
====================

Varnish is a HTTP `caching proxy`_  server which can be used to radically
improve the response time of your website.

Sulu is bundled with a "soft" `caching proxy`_, the Symfony `HttpCache`_, but
using Varnish is a more optimal solution for a large website, especially if it 
has lots of traffic.

In addition to being twice as fast as the default caching implementation it
also supports better cache invalidation, which means that your website will
appear more up-to-date.

.. note::

    "Twice as fast" is relative. The default cache implementation can respond
    in 0.02s compared to varnishes 0.01s - the difference here is
    imperceptible - but varnish will scale better and supports better
    invalidation.

This tutorial will walk you through the process of setting up Varnish on 
your own server and configuring it to work with Sulu.

This tutorial assumes that:

- You are using the Apache2 web server
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

.. note::

    You may skip this section if you intend to run varnish in a development
    environment and do not want to change the default port of your web server.

For a caching server to serve pages to your users, it will need to "pretend"
to be the web server. Web servers listen to requests on port 80 by default. We
must make Varnish listen for connections on port 80 and make the web server
listen on a different port.

.. note::

    We are going to make the web server listen on port `8090` but there is
    nothing special about this port and it can be anything as long as it does
    not conflict with any existing services.

Change the ``Listen`` directive in ``/etc/apache2/ports.conf`` to ``Listen 8090``:

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

.. note::

    Skip this section if you are in a development environment and prefer to
    access varnish via. its default port (explained later).

By default Varnish will listen for connections on port ``6081`` (at least on
Debian systems). If you are running a production system you will need to
change this to the default HTTP port, port ``80``.

Verify which port Varnish is listening to:

.. code-block:: bash

    $ ps ax | grep varnish
    6585 ?        SLs    0:00 varnishd -f /home/daniel/.varnish/sulu.vcl -s malloc,1G -T 127.0.0.1:2000 -a 0.0.0.0:6081
    6609 ?        Sl     0:07 varnishd -f /home/daniel/.varnish/sulu.vcl -s malloc,1G -T 127.0.0.1:2000 -a 0.0.0.0:6081

The ``-a`` option indicates where Varnish is listening - it is listening on port ``8083``, which is incorrect.

Under Debiae/Ubuntu we can change the initialization script:

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

    C{
        #include <stdlib.h>
    }C

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

        // Check for ESI acknowledgement and remove Surrogate-Control header
        if (beresp.http.Surrogate-Control ~ "ESI/1.0") {
            unset beresp.http.Surrogate-Control;
            set beresp.do_esi = true;
        }

        if (beresp.http.X-Reverse-Proxy-TTL) {
            /*
             * Note that there is a ``beresp.ttl`` field in VCL but unfortunately
             * it can only be set to absolute values and not dynamically. Thus we
             * have to resort to an inline C code fragment.
             *
             * As of Varnish 4.0, inline C is disabled by default. To use this
             * feature, you need to add `-p vcc_allow_inline_c=on` to your Varnish
             * startup command.
             */
            C{
                const char *ttl;
                const struct gethdr_s hdr = { HDR_BERESP, "\024X-Reverse-Proxy-TTL:" };
                ttl = VRT_GetHdr(ctx, &hdr);
                VRT_l_beresp_ttl(ctx, atoi(ttl));
            }C

            unset beresp.http.X-Reverse-Proxy-TTL;
        }
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

You will first need to ensure that the default "soft" cache has been disabled.

Open the website front controller (``app/website.php`` in the `standard
edition`_) and ensure that the following lines are commented out:

.. code-block:: php

    // Uncomment this line if you want to use the "symfony" http
    // caching strategy. See 
    // if (SYMFONY_ENV != 'dev') {
    //    require_once __DIR__ . '/../app/WebsiteCache.php';
    //    $kernel = new WebsiteCache($kernel);
    //}

.. warning::

    If you do not comment out the above lines caching will not work as you
    will be using 2 caches.

Now edit ``app/config.yml`` and change the proxy client from ``symfony`` to
``varnish`` and set the address of your varnish server (assuming that your
Varnish server is on localhost and listening on port ``80``):

.. code-block:: yaml

    sulu_http_cache:
        # ...
        proxy_client:
            varnish:
                enabled: true
                servers: [ 'localhost:80' ]

Now have another look at the headers from your website:

.. code-block:: bash

    $ curl -I sulu.lo
    HTTP/1.1 200 OK
    Host: sulu.lo:6081
    Cache-Control: max-age=1000, public, s-maxage=1000
    Date: Fri, 16 Jan 2015 13:46:58 GMT
    Content-Type: text/html; charset=UTF-8
    X-Reverse-Proxy-TTL: 2400
    X-Sulu-Handlers: public, debug
    X-Sulu-Proxy-Client: varnish
    X-Sulu-Structure-Type: AnimalsPageCache
    X-Sulu-Structure-UUID: 26f73515-253b-4f98-a227-8811b830735d
    X-Sulu-Page-TTL: 2400
    X-Debug-Token: f05a02
    X-Debug-Token-Link: /_profiler/f05a02
    X-Varnish: 131202 32868
    Age: 88
    Via: 1.1 varnish-v4
    X-Cache: HIT
    Content-Length: 9240
    Connection: keep-alive

.. note::

    If you chose not to make Varnish listen on port 80, then use ``sulu.lo:6081`` instead.

The meaning of all these headers will be explained in the
:doc:`../bundles/http_cache` document. But for now you should see
(providing your are in `dev` mode) the ``X-Sulu-Proxy-Client`` has a value of
``varnish``.

Optimal configuration
---------------------

To get the most out of the Varnish cache you should enable the ``tags`` cache
handler and disable the ``paths`` handler.  

The ``tags`` handler will automatically ensure that any changes you make in the
admin interface are immediately available on your website.

See the :doc:`../bundles/http_cache` document for more information.

The following is a full configuration example:

.. code-block:: yaml

    sulu_http_cache:
        handlers:
            tags:
                enabled: true
            public:
                max_age: 240 # 4 minutes
                shared_max_age: 480 # 8 minutes
                use_page_ttl: true
                enabled: true
            debug:
                enabled: %kernel.debug%
        proxy_client:
            varnish:
                enabled: true
                servers: [ '127.0.0.1:80' ]

.. _caching proxy: https://en.wikipedia.org/wiki/Proxy_server
.. _HttpCache: http://symfony.com/doc/current/book/http_cache.html
.. _standard edition: http://github.com/sulu/sulu-standard
