Improve Sitemap Speed
=====================

The sitemap of Sulu is based on small pieces, which are generated
by so called `SitemapProvider` (see :doc:`sitemap-provider`).
Each provider returns mostly 50000 links which can return many
links, which would take a bigger amount of time. The Google bot
does not wait a long time for the sitemap to be returned.
Therefore Sulu is able to pre-generate the whole sitemap and
cache it on the filesystem. This can be triggered by calling the
following command. This can also be done by a cron-job.

Before you start generating the sitemap, make sure that you have
properly configured the ``default_host`` parameter.

.. code-block:: yaml

    sulu_website:
        sitemap:
            default_host: http://example.com


.. code-block:: bash

    `bin/websiteconsole sulu:website:dump-sitemap`
