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

The ``-a`` option indicates where Varnish is listening - it is listening on port ``6081``, which is incorrect.

Under Debian/Ubuntu we can change the initialization script:

.. code-block:: bash

    # /etc/default/varnish

    # ...
    DAEMON_OPTS="-a :80 \
                 -T localhost:6082 \
                 -f /etc/varnish/default.vcl \
                 -S /etc/varnish/secret \
                 -s malloc,256m \
                 -p "vcc_allow_inline_c=on"

Now restart the daemon:

.. code-block:: bash

    /etc/init.d/varnishd restart

Varnish Configuration
---------------------

The following will add full caching support for Sulu:

.. code-block:: varnish4

    # /etc/varnish/default.vcl
    vcl 4.0;

    include "<PATH-TO-SULU-PROJECT>/vendor/sulu/sulu/src/Sulu/Bundle/HttpCacheBundle/Resources/varnish/sulu.vcl";

    acl invalidators {
        "localhost";
    }

    backend default {
        .host = "127.0.0.1";
        .port = "8090";
    }

    sub vcl_recv {
        call sulu_recv;

        # Force the lookup, the backend must tell not to cache or vary on all
        # headers that are used to build the hash.
        return (hash);
    }

    sub vcl_backend_response {
        call sulu_backend_response;
    }

    sub vcl_deliver {
        call sulu_deliver;
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

Open the website front controller (``public/index.php`` in the `skeleton`_) and
ensure that the following lines are commented out:

.. code-block:: php

    // if ('dev' !== $_SERVER['APP_ENV'] && SuluKernel::CONTEXT_WEBSITE === $suluContext) {
    //     $kernel = $kernel->getHttpCache();
    // }

.. warning::

    If you do not comment out the above lines caching will not work correctly as you
    will be using 2 caches.

Now edit ``config/packages/sulu_http_cache.yml`` and change the proxy client
from ``symfony`` to ``varnish`` and set the address of your varnish server
(assuming that your Varnish server is on localhost and listening on port ``80``):

.. code-block:: yaml

    sulu_http_cache:
        # ...
        proxy_client:
            varnish:
                enabled: true
                servers: ['localhost:80']

Using XKey
----------

Xkey is an efficient way to invalidate Varnish cache entries based on tagging. The advantage of
the feature is that you can use the ``grace mode`` feature of varnish, which allows varnish to 
serve expired cache entries while fetching an update from the backend transparently. 
Have a look at the varnish documentation for more information about
the `Grace mode`_.


Unfortunately, varnish does not support XKey invalidation out of the box. 
To be able to use it, you need to install ``varnish-modules``:

.. code-block:: bash

    apt-get install varnish-modules

Or build it from sources see the documentation at the github repository `varnish/varnish-modules`_.

When the installation was successfull you can use following configuration to enable
xkey:

.. code-block:: varnish4

    # /etc/varnish/default.vcl
    vcl 4.0;

    include "<PATH-TO-SULU-PROJECT>/vendor/sulu/sulu/src/Sulu/Bundle/HttpCacheBundle/Resources/varnish/sulu.vcl";
    include "<PATH-TO-SULU-PROJECT>/vendor/friendsofsymfony/http-cache/resources/config/varnish/fos_tags_xkey.vcl";

    acl invalidators {
        "localhost";
    }

    backend default {
        .host = "127.0.0.1";
        .port = "8090";
    }

    sub vcl_recv {
        call fos_tags_xkey_recv;
        call sulu_recv;

        # Force the lookup, the backend must tell not to cache or vary on all
        # headers that are used to build the hash.
        return (hash);
    }

    sub vcl_backend_response {
        set beresp.grace = 2m;

        call sulu_backend_response;
    }

    sub vcl_deliver {
        call fos_tags_xkey_deliver;
        call sulu_deliver;
    }

Additionally, you need to configure Sulu to use the XKey feature of varnish:

.. code-block:: yaml

    sulu_http_cache:
        proxy_client:
            varnish:
                enabled: true
                servers:
                  - '%env(resolve:VARNISH_SERVER)%'
                tag_mode: purgekeys

Optimal configuration
---------------------

To get the most out of the Varnish cache you should enable the ``tags`` option in the configuration.

.. code-block:: yaml

sulu_http_cache:
    tags:
        enabled: true

The ``tags`` option will automatically ensure that any changes you make in the
admin interface are immediately available on your website.

See the :doc:`../bundles/http_cache` document for more information.

The following is a full configuration example:

.. code-block:: yaml

    sulu_http_cache:
        debug:
            enabled: true
        tags:
            enabled: true
        cache:
            max_age: 240 # 4 minutes
            shared_max_age: 480 # 8 minutes
        proxy_client:
            symfony:
                enabled: false
            varnish:
                enabled: true
                servers: [ '127.0.0.1:80' ]

.. _caching proxy: https://en.wikipedia.org/wiki/Proxy_server
.. _HttpCache: http://symfony.com/doc/current/book/http_cache.html
.. _skeleton: http://github.com/sulu/skeleton
.. _Grace mode: https://varnish-cache.org/docs/trunk/users-guide/vcl-grace.html
.. _varnish/varnish-modules: https://github.com/varnish/varnish-modules#installation
