Running Sulu in a Multi-Server Setup
=====================================

Sulu is built with horizontal scaling in mind and allows to distribute the load of your application between multiple servers.
In such a setup, it is important to configure your application to use centralized solutions for session management, cache management,
search indexes and media storage.


Media Storage
-------------

Media files that are uploaded in the media section of the Sulu administration interface are stored in the filesystem of the
current server by default. If your application runs on multiple servers, you need to :doc:`configure an external storage adapter<./external-media-storage>`
for your media files.


HTTP Cache
----------

Sulu utilizes the `FOSHttpCacheBundle`_ to improve response times by caching rendered pages. This cache is automatically invalidated
when the content of a page is changed. To prevent different cache entries on different servers, you need to :doc:`configure Varnish as a centralized caching proxy<./caching-with-varnish>`.


Application Cache
-----------------

The Symfony cache improves the speed of your application by caching metadata, doctrine results and PHPCR data.
If your application runs on multiple servers, you need to `configure a centralized caching adapter`_ like ``redis`` for your ``app`` cache.
Additionally, if the application runs in different directories on different servers, you need to set a static ``prefix_seed``:

.. code-block:: yaml

    # config/packages/cache.yaml
    framework:
        cache:
            app: cache.adapter.redis
            prefix_seed: your_vendor_name/app_name
            default_redis_provider: "redis://127.0.0.1:6379"


Search Index
------------

Sulu uses the `MassiveSearchBundle`_ for its search functionality on the website and in the administration interface.
By default the bundle creates an optimized search index in the current server. To prevent outdated search results,
you need to `configure Elasticsearch as a centralized search adapter`_.

Session Management
------------------

By default, Symfony stores active sessions in the filesystem of the current server. To prevent random logouts between requests,
you need to manage your sessions in a centralized storage that is accessed by all your servers. Have a look at the
`Store Sessions in a Database`_ section of the Symfony documentation to find out how to store sessions in a database like Redis or MySQL.
Alternatively, you can directly set a centralizes ``session.save_handler`` in your ``php.ini``:

.. code-block::

    session.save_handler = redis
    session.save_path = "tcp://127.0.0.1:6379"


.. _MassiveSearchBundle: https://github.com/massiveart/MassiveSearchBundle
.. _FOSHttpCacheBundle: https://github.com/friendsofsymfony/FOSHttpCacheBundle
.. _Store Sessions in a Database: https://symfony.com/doc/current/session/database.html
.. _configure a centralized caching adapter: https://symfony.com/doc/current/cache.html
.. _configure Elasticsearch as a centralized search adapter: https://massivesearchbundle.readthedocs.io/en/latest/search_adapters.html#elasticsearch

