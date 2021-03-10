Building the administration interface frontend application
==========================================================

The administration interface of Sulu is implemented as a single page application using React. The code of this 
application is built using webpack and stored in the `public/build/admin` directory of the project. 
If you update your Sulu version or add custom Javascript code to your project, you need to update the build in 
the `public/build/admin directory`. There are different ways to do this:

Solution 1: Update Command (Recommended way)
--------------------------------------------

Sulu is shipped with a build in command to update the build.

1. Run Update Build command

.. code-block:: bash

   bin/adminconsole sulu:admin:update-build

.. note::

   The update build command will download the build for your project from the `sulu/skeleton repository`_ if possible.
   If you have added custom Javascript code in your project, the command will automatically cleanup leftovers from
   previous builds and build the Javascript code manually on your system. Make sure that you have installed `node`_ if 
   your project requires a manual build.

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

.. _sulu/skeleton repository: https://github.com/sulu/skeleton
.. _node: https://nodejs.org/en/
