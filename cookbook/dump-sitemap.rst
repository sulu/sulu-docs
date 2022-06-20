Improve Sitemap Speed
=====================

The sitemap of Sulu is based on small pieces, which are generated
by so called `SitemapProvider` (see :doc:`sitemap-provider`).
Each provider returns mostly 50000 links which can return many
links, which would take a bigger amount of time. The Google bot
does not wait a long time for the sitemap to be returned.

To improve the speed of the sitemap page, Sulu provides a command for pre-generating 
the page and cache it on the filesystem. The should be called in a cron-job to keep the
pre-generated sitemap up to date.

.. note::

    This is a performance optimization for very big websites. In 99% of the cases, the 
    optimization is not necessary and the sitemap can be generated on the fly. 

.. code-block:: bash

    bin/websiteconsole sulu:website:dump-sitemap

If you use the ``{host}`` replacer in your webspace url
configuration, you need to set the Symfony ``default_uri`` config option
for generating the URLs of your sitemap items via a command. 
Have a look at the official Symfony Documentation about 
`Generating URLs in Commands`_ for more information.

.. code-block:: yaml

    # config/packages/framework.yaml
    framework:
        router:
            default_uri: 'https://example.org'

.. tip::

    You can use ``%env(DEFAULT_URI)%`` to set the configuration
    via an environment variables.

If you are using a Symfony version before 5.1, you need to configure the
`router context`_  parameters instead of the ``default_uri`` option:

.. code-block:: yaml

    # config/services.yaml
    parameters:
        router.request_context.scheme: 'https'
        router.request_context.host: 'example.org'

Switch back to on the fly generation
------------------------------------

If you want to switch back to on the fly generation, you need
to remove the exist pre-generated sitemaps from the ``var`` directory.

By default, pre-generated sitemaps are stored in the following directory in the 
``prod`` environment:

.. code-block:: bash

    rm -rf var/cache/website/prod/sulu/sitemaps/

.. _router context: https://symfony.com/doc/4.4/routing.html#generating-urls-in-commands
.. _Generating URLS in Commands: https://symfony.com/doc/5.4/routing.html#generating-urls-in-commands
