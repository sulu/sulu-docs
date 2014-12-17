Caching
=======

SuluCMF aims to be pretty fast by default, but there is a limit to how much
work your web server can perform. Caching systems defend your server by
remembering the responses from your web application and returning these
"cached" responses instead of asking your web application to render them.

Cached responses are lightening fast, but if your content changes then these
cached responses could be out-dated - ensuring that cached pages present
the correct data is a challenge.

Sulu currently lets you approach the problem in two ways:

- **Setting the lifetime of cached responses**: Set how long cached responses
  should be used before "refreshing" them from the web application.
- **Invalidating the cache when content is updated**: The cache will be
  automatically invalidated when Sulu content changes.

.. note::

    Sulu includes the `FOSHttpCacheBundle`_ and this can be used as described
    in the `FOSHttpCacheBundle documentation`_.

Enable Caching
--------------

To enable caching you must add ``SuluHttpCacheBundle`` and
``FOSHttpCacheBundle`` to your Kernel (if
it is not already registered):

.. code-block:: php

    <?php
    // app/AppKernel.php

    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                // ...
                new Sulu\Bundle\HttpCacheBundle\SuluHttpCacheBundle(),
                new FOS\HttpCacheBundle\FOSHttpCacheBundle(),
            );

            // ...
        }
    }

Now enable all the handlers:

.. code-block:: yaml

    # app/config/config.yml
    sulu_http_cache: 
        structure_cache_handlers: 
            aggregate: 
                handlers: [ standard, paths, tags ]

Cache Handlers
--------------

Cache Handlers are classes which manage different ways of invalidating the
cache.

For caching to take place **you must enable some or all of these handlers**.

They can be enabled by configuring the ``aggregate`` handler;
The following example shows you how to enable all the handlers:

``aggregate``
~~~~~~~~~~~~~

The aggregate handler is not a real cache handler at all! It holds a selection of other
handlers which it delegates to. The configuration for this handler determines which
handlers will be active:

.. code-block:: yaml

    # app/config/config.yml
    sulu_http_cache: 
        structure_cache_handlers: 
            aggregate: 
                handlers: [ standard, paths, tags ]

``standard``
~~~~~~~~~~~~

Adds the ``max-age`` and ``shared-max-age`` headers to the response in
addition to adding the structures TTL. In plain terms it defines how
long the request should be cached for:

.. code-block:: yaml

    # app/config/config.yml
    sulu_http_cache: 
        structure_cache_handlers: 
            // ...
            standard: 
                max_age: 400 
                shared_max_age: 1000 

``paths``
~~~~~~~~~

Will automatically invalidate all of the URLs associsted with a Sulu Page
when that page is saved.

``tags``
~~~~~~~~

The tag handler will automatically add tags to the response, for example:

.. code-block: bash

    HTTP/1.1 200 OK
    X-Cache-Tags: structure-4dfd5a3a-822e-4f21-b90e-b2ae93907dc1,structure-685c5542-0051-4ce9-a22e-9c4ca438447d

The caching proxy will then remeber that this response is associated with these tags.

Subsequently, when a content is updated all pages which are associated with that page
will also be invalidated.

.. _FOSHttpCacheBundle: https://github.com/FriendsOfSymfony/FOSHttpCacheBundle
.. _FOSHttpCacheBundle documentation: http://foshttpcachebundle.readthedocs.org/
