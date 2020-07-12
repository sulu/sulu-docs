Using Jackrabbit
================

If you maintain a bigger website, it might make sense to use Jackrabbit instead of
the ``doctrine-dbal`` implementation of Jackalope. Jackrabbit performs better in many
cases, and as a bonus it also supports :doc:`../bundles/page/versioning` of content.

Installation
------------

Use the following command to install the ``jackrabbit`` adapter:

.. code-block:: bash

   composer require jackalope/jackalope-jackrabbit

In addition to the previous command you also have to make sure that `Jackrabbit`_ is running
on your server.

Configuration
-------------

Change the ``config/packages/sulu_document_manager.yaml`` file to something similar as
below. Mind that it is recommended to pass the URL via an `environment variable`_, which
can e.g. be set in your ``.env`` file.

.. code-block:: yaml

    parameters:
        env(JACKRABBIT_URL): 'http://localhost:8080/server/'

    sulu_document_manager:
        sessions:
            default:
                backend:
                    type: jackrabbit
                    url: "%env(JACKRABBIT_URL)%"
                workspace: "%env(PHPCR_WORKSPACE)%"
            live:
                backend:
                    type: jackrabbit
                    url: "%env(JACKRABBIT_URL)%"
                workspace: "%env(PHPCR_WORKSPACE)%_live"

.. note::

    The ``PHPCR_WORKSPACE`` is something similar as a database name so it is best practice
    to have a similar value for it, for example: ``su_myproject`` in your ``.env`` files.

Migration
---------

In order to migrate from ``doctrinedbal`` to ``jackrabbit`` you have to export your
data before changing the configuration:

.. code-block:: bash

    bin/adminconsole doctrine:phpcr:workspace:export -p /cmf cmf.xml
    bin/websiteconsole doctrine:phpcr:workspace:export -p /cmf cmf_live.xml
    bin/adminconsole doctrine:phpcr:workspace:export -p /jcr:versions jcr.xml

Then change the configuration as explained in the above Configuration section, and
then execute the following command to initialize the jackrabbit workspaces for sulu:

.. code-block:: bash

    bin/adminconsole cache:clear
    bin/adminconsole sulu:document:initialize

Now executed these commands to clear any previously existing data (first you should make
sure that you really don't need this data anymore).

.. code-block:: bash

    bin/adminconsole doctrine:phpcr:node:remove /cmf
    bin/websiteconsole doctrine:phpcr:node:remove /cmf
    # the following command can fail if the node not exist ignore the error then:
    bin/adminconsole doctrine:phpcr:node:remove /jcr:versions

After that you can import the exported data from ``doctrinedbal`` into ``jackrabbit``
by running the following commands:

.. code-block:: bash

    bin/adminconsole doctrine:phpcr:workspace:import -p / cmf.xml
    bin/websiteconsole doctrine:phpcr:workspace:import -p / cmf_live.xml
    bin/adminconsole doctrine:phpcr:workspace:import -p / jcr.xml

.. _`Jackrabbit`: https://jackrabbit.apache.org/jcr/index.html
.. _`environment variable`: https://symfony.com/doc/4.4/configuration.html#config-env-vars
