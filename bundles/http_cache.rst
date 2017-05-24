HttpCacheBundle
===============

The SuluHttpCache bundle provides tight integration between Sulu and HTTP caching proxies.

Handlers
--------

All of the functionality of the Sulu HTTP cache implementation is encapsulated
in "handlers". Handlers are responsible primarily for handling the caching of
Sulu ``Structure`` objects (e.g. ``Page`` and ``Snippet`` classes).

Most handlers will need to talk to a HTTP proxy cache implementation. The cache
implementations are provided by the `FOSHttpCache`_ component. Examples of
handlers which do not need to talk to a cache implementation are the
``DebugHandler`` and the ``PublicHandler``.

Handler classes implement the ``HandlerInterface`` and are further specialized with three further interfaces:

- ``HandlerFlushInterface``: This handler is capable of being "flushed" - this will normally be a proxy for ``ProxyClient#flush``.
- ``HandlerInvalidateInterface``: This handler can invalidate the cache.
- ``HandlerUpdateResponseInterface``: This handler can update the response.

Aggregate Handler
"""""""""""""""""

Implements: **flush**, **invalidate** and **update response**.

The aggregate handler is special as it aggregates other handlers so that you can
apply multiple handlers at the same time.

This is the default handler and by default it will aggregate all enabled handlers.

Debug Handler
"""""""""""""

Implements: **update response**.

The debug handler simply adds debug information to your response:

- ``x-sulu-handler``: The handlers which are enabled
- ``x-sulu-proxy-client``: The  name of the proxy client being used
- ``x-sulu-structure-type``: The structure type for which the response is being returned
- ``x-sulu-structure-uuid``: The UUID of the Structure
- ``x-sulu-page-ttl``: Send the TTL (`time to live`_) to the proxy client with the header `X-Resverse-Proxy-TTL`
  (which is defined as a constant in the ``Sulu\Component\HttpCache`` class).

For example:

.. code-block:: bash

    x-sulu-handlers: paths, public, debug
    x-sulu-proxy-client: symfony
    x-sulu-structure-type: OverviewPageCache
    x-sulu-structure-uuid: 22a92d46-74ab-46cc-b47c-486b4b8a06a7
    x-sulu-page-ttl: 2400

Paths Handler
"""""""""""""

Implements: **flush**, **invalidate** and **update response**.

The paths handler will invalidate all the URLs associated with the subject Structure when it is updated. For
example, if a page is located at ``http://sulu.lo/de/my/page`` and ``http://sulu.lo/en/my/page`` then these two
URLs will be purged in the cache, and their contents will be updated when they are next requested.

.. note::

    When using the paths handler you need to be aware that pages which reference the structure being invalidated
    will **not be updated** - for example pages which aggregate the subject structure in a SmartContent content type.

Public Handler
""""""""""""""

Implements: **update response**.

The public handler adds generic caching information to the response which will be consumed by the users
web browser. For example a ``max-age`` time of 3600 will instruct the users browser to locally cache the page
for one hour before requesting it from the server again.

The following are example headers added by the public handler (the
``X-Reverse-Proxy`` header will normally be removed by the cache
implementation):

.. code-block:: bash

    Cache-Control: max-age=240, public, s-maxage=240
    X-Reverse-Proxy-TTL: 2400

Tags Handler
""""""""""""

The tags handler is the most comprehensive cache invalidation strategy, it will
invalidate both the URLs of the structure and the URLs of the pages which
display references to the structure. . It must be used in conjunction with a
proxy client which supports Banning. Currently **this handler can only be used
with Varnish**.

This handler works by sending all of the UUIDs of the structures which are
contained in a page response to the proxy client. The proxy client can then
store this information along with the cached HTML response. 

When you update any structure in the admin interface it will instruct the HTTP proxy
to purge all the caches which have a reference to the UUID of the structure you
have updated.

Example header sent by the tags handler (which will be removed by varnish):

.. code-block:: bash

    X-Cache-Tags: 22a92d46-74ab-46cc-b47c-486b4b8a06a7,cf4a07fe-91d0-41be-aed8-b1c9ee1eb72a

This header will be written at the end of the response by using the
:doc:`content/reference-store`. This service collects the
entities/documents which was used to render the page.

Proxy Clients
-------------

Symfony Http Cache
""""""""""""""""""

The Symfony HTTP cache is the default caching client for Sulu CMF. It is integrated
directly into Sulu.

It works by "wrapping" the kernel. You can find it in the website front controller ``web/website.php``:

.. code-block:: php

    // web/website.php
    // ...

    // Comment this line if you want to use the "varnish" http
    // caching strategy. See http://sulu.readthedocs.org/en/latest/cookbook/caching-with-varnish.html
     if (SYMFONY_ENV != 'dev') {
        require_once __DIR__ . '/../app/WebsiteCache.php';
        $kernel = new WebsiteCache($kernel);
    }

It will need to be disabled when using varnish.

Varnish
-------

The varnish proxy client is provided by the `FOSHttpCache`_ component.

See :doc:`../../cookbook/caching-with-varnish` for more information about setting up
varnish.

Default configuration
---------------------

.. code-block:: yaml

    # Default configuration for extension with alias: "sulu_http_cache"
    sulu_http_cache:

        default_handler:      aggregate

        # Configuration for structure cache handlers
        handlers:
            aggregate:
                enabled:              true

                # Handlers to aggregate, e.g. all or any of tags, path, public
                handlers:             []
            public:
                enabled:              false
                max_age:              300
                shared_max_age:       300

                # Use the dynamic pages cache lifetime for reverse proxy server
                use_page_ttl:         true
            paths:
                enabled:              false
            tags:
                enabled:              false
            debug:
                enabled:              false
        proxy_client:
            symfony:
                enabled:              false
            varnish:
                enabled:              false

                # Addresses of the hosts Varnish is running on. May be hostname or ip, and with :port if not the default port 80.
                servers:              # Required

                    # Prototype
                    name:                 ~

                # Default host name and optional path for path based invalidation.
                base_url:             null


.. _FOSHttpCache: https://github.com/friendsofsymfony/FOSHttpCache
.. _time to live: http://en.wikipedia.org/wiki/Time_to_live
