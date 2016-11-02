Improve Sitemap Speed
=====================

The sitemap of Sulu is based on small pieces, wich are generated
by so called `SitemapProvider` (see :doc:`sitemap-provider`).
Each provider returns mostly 50000 links which can return many
links, which would take a bigger amount of time. The Google bot
does not wait a long time for the sitemap to be returned.
Therefore Sulu is able to pre-generate the whole sitemap and
cache it on the filesystem. This can be triggered by calling the
following command. This can also be done by a cron-job.

.. code-block:: bash

    app/websiteconsole sulu:website:dump-sitemap
