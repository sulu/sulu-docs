Optimize for production usage
=============================

If you want to use Sulu in production there are a few more optimizations you
can do than just switching to the `prod` environment. The Symfony documentation
already gives an introduction into `deploying applications`_. Since Sulu is
also a Symfony application all these tips also apply to deploying Sulu.

This cookbook entry will show even more ways to optimize the performance of
Sulu in a production environment.

Enable doctrine caches
----------------------

The Symfony documentation already describes how to activate caching for the
metadata, queries and results in its `DoctrineBundle documentation`_.

If you have APC installed and want to enable caching using APC you can just
uncomment the following lines in `app/config/admin/config_prod.yml` and
`app/config/website/config_prod.yml`:

.. code-block:: yaml

    doctrine:
        orm:
            metadata_cache_driver: apc
            result_cache_driver: apc
            query_cache_driver: apc

In case you want to use other caching providers you should have a look at the
`DoctrineBundle documentation`_, where the configuration of other providers is
explained. 

.. _deploying applications: http://symfony.com/doc/current/cookbook/deployment/tools.html
.. _DoctrineBundle documentation: http://symfony.com/doc/current/reference/configuration/doctrine.html#caching-drivers
