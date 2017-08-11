HttpCacheBundle
===============

The SuluHttpCache bundle provides integration between Sulu and HTTP caching proxies using the `FOSHttpCacheBundle`_.

CacheManager
------------

The CacheManager can be used to invalidate the cache.

For example:

.. code-block:: php

    ...
    $cacheManager = $this->get('sulu_http_cache.cache_manager');
    $cacheManager->invalidatePath($path, $headers);
    ...

CacheLifetime
-------------

CacheLifetimeEnhancer
"""""""""""""""""""""

Use this service to set the cache lifetime from a Sulu structure to the response.
The structure needs to be an instance of `PageInterface`.

Note: This service is only available when a proxy client is correctly configured.

For example:

.. code-block:: php

    ...
    if ($this->has('sulu_http_cache.cache_lifetime.enhancer')) {
        $cacheLifetimeEnhancer = $this->get('sulu_http_cache.cache_lifetime.enhancer');

        $cacheLifetimeEnhancer->enhance($response, $structure);
    }
    ...

CacheLifetimeResolver
"""""""""""""""""""""

The cache lifetime resolver resolves the given cache lifetime metadata based on the type
and returns an absolute cache lifetime in seconds.

Configuration
-------------

Debug
"""""

The debug feature simply adds debug information to your response:

For example:

.. code-block:: bash

    X-Cache: HIT/MISS

Enable this feature via configuration:

.. code-block:: yaml

sulu_http_cache:
    debug:
        enabled: true

Tags
""""

The tags feature is the most comprehensive cache invalidation strategy, it will
invalidate both the URLs of the structure and the URLs of the pages which
display references to the structure. It must be used in conjunction with a
proxy client which supports Banning. Currently **this is only possible with Varnish**.

This works by sending all of the UUIDs of the structures which are
contained in a page response to the proxy client. The proxy client can then
store this information along with the cached HTML response.

When you update any structure in the admin interface it will instruct the HTTP proxy
to purge all the caches which have a reference to the UUID of the structure you
have updated.

Example header sent by the tags handler (which will be removed by varnish):

.. code-block:: bash

    X-Cache-Tags: 22a92d46-74ab-46cc-b47c-486b4b8a06a7,cf4a07fe-91d0-41be-aed8-b1c9ee1eb72a

This header will be written at the end of the response by using the
:doc:`website/reference-store`. This service collects the
entities/documents which were used to render the page.

Enable this feature via configuration:

.. code-block:: yaml

sulu_http_cache:
    tags:
        enabled: true

Proxy Clients
-------------

At the moment Sulu works with following proxy clients:

Symfony Http Cache
""""""""""""""""""

The Symfony HTTP cache is the default caching client for Sulu. It is integrated directly into Sulu.

It works by "wrapping" the kernel. You can find it in the website front controller ``web/website.php``:

.. code-block:: php

    // web/website.php
    // ...

    // Comment this line if you want to use the "varnish" http
    // caching strategy. See http://sulu.readthedocs.org/en/latest/cookbook/caching-with-varnish.html
    if (SYMFONY_ENV !== 'dev') {
        $kernel = new WebsiteCache($kernel);

        // When using the HttpCache, you need to call the method in your front controller
        // instead of relying on the configuration parameter
        Request::enableHttpMethodParameterOverride();
    }

It will need to be disabled when using varnish.

Varnish
"""""""

The varnish proxy client is provided by the `FOSHttpCache`_ component.

See :doc:`../../cookbook/caching-with-varnish` for more information about setting up
varnish.

Default configuration
---------------------

.. code-block:: yaml

    # Default configuration for extension with alias: "sulu_http_cache"
    sulu_http_cache:
        tags:
            enabled:              false
        cache:
            max_age:              240
            shared_max_age:       240
        proxy_client:
            symfony:
                enabled:              false

                # Addresses of the hosts Symfony is running on. May be hostname or ip, and with :port if not the default port 80.
                servers:

                    # Prototype
                    name:                 ~

                # Default host name and optional path for path based invalidation.
                base_url:             null
            varnish:
                enabled:              false

                # Addresses of the hosts Varnish is running on. May be hostname or ip, and with :port if not the default port 80.
                servers:

                    # Prototype
                    name:                 ~

                # Default host name and optional path for path based invalidation.
                base_url:             null
        debug:

            # Whether to send a debug header with the response to trigger a caching proxy to send debug information. If not set, defaults to kernel.debug.
            enabled:              true


.. _FOSHttpCacheBundle: https://github.com/friendsofsymfony/FOSHttpCacheBundle
.. _FOSHttpCache: https://github.com/friendsofsymfony/FOSHttpCache
