Building Admin Frontend
=======================

Solution 1: Update Command (Recommended way)
--------------------------------------------

Sulu is shipped with a build in command to update the build.

1. Run Update Build command

.. code-block:: bash

   bin/adminconsole sulu:admin:update-build

.. note::

   If a manually build is needed for example if you added custom
   JavaScript to your Project. You need to make sure that you
   have `node`_ installed to build it.

Solution 2: Build manually with docker
--------------------------------------

1. Start a node container with the desired version and map the the current directory into the /var/project folder in the container

.. code-block:: bash

   docker run --rm --interactive --tty --volume ${PWD}:/var/project node:14.16.0 /bin/bash

   # for completion: using another node version is possible by adjusting the tag of the node image
   # docker run --rm --interactive --tty --volume ${PWD}:/var/project node:10.24.0 /bin/bash
   # docker run --rm --interactive --tty --volume ${PWD}:/var/project node:12.21.0 /bin/bash

2. Cleanup previously created node_modules folders and package-lock.json files

.. code-block:: bash

   cd /var/project
   rm -rf assets/admin/node_modules && rm -rf vendor/sulu/sulu/node_modules && rm -rf vendor/sulu/sulu/src/Sulu/Bundle/*/Resources/js/node_modules
   rm -rf assets/admin/package-lock.json && rm -rf vendor/sulu/sulu/package-lock.json && rm -rf vendor/sulu/sulu/src/Sulu/Bundle/*/Resources/js/package-lock.json

3. Create the administration interface build

.. code-block:: bash

    cd /var/project/assets/admin
    npm install
    npm run build

Solution 3: Build manually locally
----------------------------------

1. Install Node

If not yet installed on your computer you would need to install `node`_
on your computer.

2. Cleanup previously created node_modules folders and package-lock.json files

.. code-block:: bash

   cd /var/project
   rm -rf assets/admin/node_modules && rm -rf vendor/sulu/sulu/node_modules && rm -rf vendor/sulu/sulu/src/Sulu/Bundle/*/Resources/js/node_modules
   rm -rf assets/admin/package-lock.json && rm -rf vendor/sulu/sulu/package-lock.json && rm -rf vendor/sulu/sulu/src/Sulu/Bundle/*/Resources/js/package-lock.json

3. Create the administration interface build

.. code-block:: bash

    cd /var/project/assets/admin
    npm install
    npm run build

.. _node: https://nodejs.org/en/
