Jackrabbit
==========

If you want to have the `versioning`_ feature or want to improve the performance you
maybe come to the time to change from ``doctrinedbal`` phpcr implementation to ``jackrabbit``.

Installation
------------

To have the ``jackrabbit`` adapter available you need to install the implementation
using the following command:

.. code-block:: bash

   composer require jackalope/jackalope-jackrabbit

Configuration
-------------

You need to change the following in ``config/packages/sulu_document_manager.yaml`` file
its recommended to have the url as an environment variable in your .env file.

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
                workspace: "%env(PHPCR_WORKSPACE)%"

Migration
---------

If you want to migrate from ``docrinedbal`` to ``jackrabbit`` phpcr implementation before
switching export the data the following way:

.. code-block:: bash

    bin/adminconsole doctrine:phpcr:workspace:export -p /cmf cmf.xml
    bin/websiteconsole doctrine:phpcr:workspace:export -p /cmf cmf_live.xml
    bin/adminconsole doctrine:phpcr:workspace:export -p /jcr:versions jcr.xml

Then switch the configuration to jackrabbit as documented above and then first remove
maybe exist old data in your jackrabbit workspace by running the following commands:

.. code-block:: bash

    bin/adminconsole doctrine:phpcr:node:remove /cmf
    bin/websiteconsole doctrine:phpcr:node:remove /cmf
    bin/adminconsole doctrine:phpcr:node:remove /jcr:versions

After that you can import the exported data from ``doctrinedbal`` into ``jackrabbit``
by running the following commands:

.. code-block:: bash

    bin/adminconsole doctrine:phpcr:workspace:import -p / cmf.xml
    bin/websiteconsole doctrine:phpcr:workspace:import -p / cmf_live.xml
    bin/adminconsole doctrine:phpcr:workspace:import -p / jcr.xml

.. _versioning: versioning.rst
