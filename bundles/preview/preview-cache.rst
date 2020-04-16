Preview Cache
=============

The preview use a cache to improve its performance. By default it will use the configured
symfony ``cache.app`` adapter.

You can configure other adapter the following way:

.. code-block:: yaml

    sulu_preview:
        cache_adapter: "cache.app" # symfony cache adapter id

For example if you want to use redis you can do it this way:

.. code-block:: yaml

    sulu_preview:
        cache_adapter: "cache.adapter.redis"
    framework:
        cache:
            default_redis_provider: 'redis://localhost' # this is default and not needed

Read more about it in the `Symfony Cache Documentation`_.

.. _Symfony Cache Documentation: https://symfony.com/doc/4.4/cache.html#configuring-cache-with-frameworkbundle
