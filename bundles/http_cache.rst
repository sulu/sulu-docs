HttpCacheBundle
===============

The SuluHttpCache bundle provides integration between Sulu and HTTP caching proxies using the `FOSHttpCacheBundle`_.

CacheManager
------------

The CacheManager can be used to invalidate the cache. The private service
``sulu_http_cache.cache_manager`` implements ``CacheManagerInterface`` and only
exists when a proxy client is enabled. Inject it as an optional argument:

.. code-block:: yaml

    services:
        App\Service\EventService:
            arguments:
                $cacheManager: '@?sulu_http_cache.cache_manager'

.. code-block:: php

    use Sulu\Bundle\HttpCacheBundle\Cache\CacheManagerInterface;

    class EventService
    {
        public function __construct(private ?CacheManagerInterface $cacheManager)
        {
        }

        public function invalidate(string $path, array $headers = []): void
        {
            $this->cacheManager?->invalidatePath($path, $headers);
        }
    }

.. list-table::
    :header-rows: 1

    * - Method
      - Description
    * - ``invalidatePath($path, $headers)``
      - Invalidates the cache of a path, and refreshes it if the proxy client supports it.
    * - ``invalidateTag($tag)``
      - Invalidates all responses which are tagged with the tag.
    * - ``invalidateReference($alias, $id)``
      - Invalidates the tag of a resource.
        Pass its resource key as ``$alias``, see `Tags`_.
    * - ``invalidateDomain($domain)``
      - Invalidates the cache of a whole domain.
    * - ``clear()``
      - Clears the whole cache.

A method does nothing if the proxy client does not support it. ``supportsInvalidate()``,
``supportsTags()`` and ``supportsClear()`` tell what the proxy client supports.

CacheLifetime
-------------

CacheLifetimeEnhancer
"""""""""""""""""""""

Use this service to set the cache headers of a response. It is always available, as
``sulu_http_cache.cache_lifetime.enhancer`` or by autowiring ``CacheLifetimeEnhancerInterface``.

The service reads the cache lifetime of the current request. The page and article route defaults
providers take it from the ``cacheLifetime`` of the template, and a scheduled block can lower it.
Without a cache lifetime, or with 0, the response stays untouched. Otherwise the response becomes
public with ``max_age`` and ``shared_max_age`` of the ``sulu_http_cache.cache`` configuration.
The lifetime is sent in the ``X-Reverse-Proxy-TTL`` header to the reverse proxy.

To give your own route a cache lifetime, add the ``_cacheLifetime`` default in seconds
(``CacheLifetimeRequestStore::ATTRIBUTE_KEY``), for example in ``getDefaults()`` of your route
defaults provider:

.. code-block:: php

    use Sulu\Bundle\HttpCacheBundle\CacheLifetime\CacheLifetimeRequestStore;

    public function getDefaults(Route $route): array
    {
        return [
            '_controller' => EventController::class . '::indexAction',
            CacheLifetimeRequestStore::ATTRIBUTE_KEY => 3600,
        ];
    }

Then call the enhancer in the controller:

.. code-block:: php

    use Sulu\Bundle\HttpCacheBundle\CacheLifetime\CacheLifetimeEnhancerInterface;
    use Symfony\Component\HttpFoundation\Response;

    class EventController
    {
        public function __construct(private CacheLifetimeEnhancerInterface $cacheLifetimeEnhancer)
        {
        }

        public function indexAction(): Response
        {
            // ...
            $this->cacheLifetimeEnhancer->enhance($response);

            return $response;
        }
    }

The ``ContentController`` of the website calls the enhancer already.

CacheLifetimeResolver
"""""""""""""""""""""

The cache lifetime resolver resolves the given cache lifetime metadata based on the type
and returns an absolute cache lifetime in seconds.

Configuration
-------------

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
proxy client which supports tags: the Symfony HTTP cache, or Varnish with banning
(``tag_mode: ban``) or with the xkey module (``tag_mode: purgekeys``).

This works by sending all of the tags of the structures and resources which are
contained in a page response to the proxy client. The proxy client can then
store this information along with the cached HTML response.

When you update any structure in the admin interface it will instruct the HTTP proxy
to purge all the caches which have a reference to the tag of the structure you
have updated.

Example header sent by the tags handler (which will be removed by varnish):

.. code-block:: bash

    X-Cache-Tags: 22a92d46-74ab-46cc-b47c-486b4b8a06a7,cf4a07fe-91d0-41be-aed8-b1c9ee1eb72a

With ``tag_mode: purgekeys`` the header is ``xkey`` and the tags are separated by a space.

This header will be written at the end of the response by using the
:doc:`reference-store`. This service collects the
entities/documents which were used to render the page.

The feature is enabled by default and takes effect as soon as a proxy client
is enabled. Disable it via configuration:

.. code-block:: yaml

    sulu_http_cache:
        tags:
            enabled: false

A tag is the UUID of a page, snippet or article, and ``<resourceKey>-<id>`` for any
other resource, for example ``contacts-3`` or ``tags-5``. Use the resource key of the
resource for the :doc:`reference-store` and for ``invalidateReference()``.

Sulu invalidates the tag of a page, snippet or article when it is published, unpublished or removed.

Proxy Clients
-------------

At the moment Sulu works with following proxy clients:

Symfony Http Cache
""""""""""""""""""

The Symfony HTTP cache is the default caching client for Sulu. It is integrated directly into Sulu.

It works by "wrapping" the kernel. This is done by the kernel itself, but in the front controller
at ``public/index.php`` the cache kernel has to be used:

.. code-block:: php

    // ...
    if ('dev' !== $_SERVER['APP_ENV'] && SuluKernel::CONTEXT_WEBSITE === $suluContext) {
        $kernel = $kernel->getHttpCache();
    }

It will need to be disabled (i.e. the lines need to be removed) when using varnish.

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
            enabled:              true
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

                # Use the purgekeys mode for more efficient tag handling, if your Varnish server supports the xkey module
                tag_mode:             ban # One of "ban"; "purgekeys"

                # HTTP header to use when sending tag invalidation requests to Varnish
                tags_header:          ~
        debug:

            # Whether to send a debug header with the response to trigger a caching proxy to send debug information. If not set, defaults to kernel.debug.
            enabled:              true


.. _FOSHttpCacheBundle: https://github.com/friendsofsymfony/FOSHttpCacheBundle
.. _FOSHttpCache: https://github.com/friendsofsymfony/FOSHttpCache
