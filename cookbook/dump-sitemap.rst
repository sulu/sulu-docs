Improve Sitemap Speed
=====================

The sitemap of Sulu is based on small pieces, which are generated
by so called `SitemapProvider` (see :doc:`sitemap-provider`).
Each provider returns mostly 50000 links which can return many
links, which would take a bigger amount of time. The Google bot
does not wait a long time for the sitemap to be returned.
Therefore Sulu is able to pre-generate the whole sitemap and
cache it on the filesystem. This can be triggered by calling the
following command. This should be be done by a cron-job.

If you use the ``{host}`` replacer in your webspace url
configuration make sure you have the symfony `router context`_
parameter configured.

.. code-block:: yaml

    # config/services.yaml
    parameters:
        router.request_context.host: 'example.org'

.. code-block:: bash

    bin/websiteconsole sulu:website:dump-sitemap

.. _router context: https://symfony.com/doc/4.4/routing.html#generating-urls-in-commands
