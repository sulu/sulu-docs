:orphan:

``Elasticsearch``
=============================

.. note::

    Since Sulu 3.0, the SEAL bundle is used for search functionality in the administration and on the website.
    See :doc:`../bundles/search` for how to configure it and take a look at the `SEAL documentation`_
    for more information.

To use Elasticsearch, install its adapter and set the ``SEAL_DSN`` environment variable:

.. code-block:: bash

    composer require cmsig/seal-elasticsearch-adapter

.. code-block:: bash

    # .env
    SEAL_DSN="elasticsearch://127.0.0.1:9200"

Fill the new search engine with ``bin/console cmsig:seal:reindex``, which creates missing indexes itself.
``bin/adminconsole sulu:build search`` creates them without filling them.

.. _SEAL documentation: https://php-cmsig.github.io/search/
