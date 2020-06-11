Upgrading Sulu 2.0
==================

This upgrade guide is valid for any update from any Sulu 2.0 to any version below 3.0. These updates should be less
problematic, because backwards compatability is only broken when it is really necessary to fix a bug. The following
steps describe this upgrade process.

**1. Upgrade the ``sulu/sulu`` package**

   Execute the following command in the root folder of your project.

.. code-block:: bash

    $ composer update

.. note::

   Usually that command works out of the box, but you might have to check the version constraints of the ``sulu/sulu``
   package in the ``composer.json`` file of your project. See the `Composer documentation`_ for more information.

**2. Check for changes between the versions you are upgrading in our Skeleton repository.**

   This step cannot be automated, because changes we are introducing here are possible BC breaks or might simply not
   fit your project. Look at the changes in the `Skeleton repository`_ yourself and see which ones make sense in your
   case.

**3. Check the UPGRADE.md file for BC breaks**

   The `UPGRADE.md`_ file in our ``sulu/sulu`` repository contains all changes breaking backwards compatibility. It
   might be that none of the changes described in this file break your application, depending on which parts of Sulu
   you have used. However, if something goes south, this file should contain an explanation what to change.

**4. Update the JavaScript build for the administration interface**

   Our administration interface requires a built version of its JavaScript in the `public/build/admin` folder of the
   project. We might have to update our JavaScript code to fix some bugs, so after an upgrade you have to make sure to
   have a build matching your Sulu version.

   This can happen in two different ways:

**a. Download the build from the `Skeleton repository`_**

   In case you have not added any custom JavaScript to our administration interface you can use the
   pre-built JavaScript in our `Skeleton repository`_. We have implemented a Symfony command, that automatically checks
   which Sulu version you are using and downloads the correct version of the JavaScript build.

.. code-block:: bash

    $ bin/console sulu:admin:download-build

**b. Make the build yourself**

    In case you have added custom JavaScript to our administration interface, you need to make the build by yourself.
    Run the following two npm commands (requires npm version 5 or higher) in the `assets/admin` folder of your project:

.. code-block:: bash

    $ npm install
    $ npm run build

.. _Composer documentation: https://getcomposer.org/doc/articles/versions.md#writing-version-constraints
.. _Skeleton repository: https://github.com/sulu/skeleton
.. _UPGRADE.md: https://github.com/sulu/sulu/blob/release/2.0/UPGRADE.md
