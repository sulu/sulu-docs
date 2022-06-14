Improve Sitemap Speed
=====================

The sitemap of Sulu is based on small pieces, which are generated
by so called `SitemapProvider` (see :doc:`sitemap-provider`).
Each provider returns mostly 50000 links which can return many
links, which would take a bigger amount of time. The Google bot
does not wait a long time for the sitemap to be returned.

In 99% of the cases the sitemap can be generated on the fly and
so nothing is required. But for very big websites their is
a command to pre-generate the sitemap via for Sulu and cache it
on the filesystem.
This can be triggered by calling the following command. This
should be done by a cron-job.

.. code-block:: bash

    bin/websiteconsole sulu:website:dump-sitemap

If you use the ``{host}`` replacer in your webspace url
configuration make sure you have the symfony `router context`_
parameter configured. Have also a look at the official Symfony
Documentation about `Generating URLs in Commands`_.

.. code-block:: yaml

    # config/packages/framework.yaml
    framework:
        router:
            default_uri: 'https://example.org'

.. tip::

    You can use ``%env(DEFAULT_URI)%`` to control the configuration
    via environment variables.

For Symfony Versions before 5.1 you have to configure the
`router context`_  parameters:

.. code-block:: yaml

    # config/services.yaml
    parameters:
        router.request_context.scheme: 'https'
        router.request_context.host: 'example.org'

Switch back to on the fly generation
------------------------------------

If you want to switch back to on the fly generation, you need
just remove the exist generated sitemaps from the ``var`` directory.

By default it is the following directory under the ``prod``
environment:

.. code-block:: bash

    rm -rf var/cache/website/prod/sulu/sitemaps/

.. _router context: https://symfony.com/doc/4.4/routing.html#generating-urls-in-commands
.. _Generating URLS in Commands: https://symfony.com/doc/5.4/routing.html#generating-urls-in-commands
