Upgrading Sulu 2.x
==================

This upgrade guide describes how to upgrade a Sulu 2.x project to any newer version below 3.0. In a majority of cases,
these upgrades should be unproblematic, because backwards compatibility is only broken when it is really necessary to
fix a bug. The upgrade process consists of the following steps:

**1. Update the sulu/sulu package**

   The ``sulu/sulu`` package implement the functionality of the Sulu content management system. Execute the following
   command in the root folder of your project to update it to the newest version:

.. code-block:: bash

    $ composer update

.. note::

   If the command does not update the ``sulu/sulu`` package to the desired version, you might need to update the
   version constraints of the ``sulu/sulu`` package in the ``composer.json`` file of your project.
   See the `Composer documentation`_ for more information.

**2. Examine the changes between the versions in the sulu/skeleton repository.**

   The `sulu/skeleton repository`_ contains the project template for Sulu projects. The template might be adjusted
   between different versions to include configuration for new features or keep up with the `Symfony best practices`_.
   It is advised to examine the `changes in the sulu/skeleton repository`_ between the versions you are upgrading and
   apply them to your project if the make sense in your case.

   This step cannot be automated, because changes in sulu/skeleton repository could include BC breaks or might simply
   not fit your project.

**3. Check the UPGRADE.md file for BC breaks**

   The `UPGRADE.md file`_ in the ``sulu/sulu`` repository contains all changes breaking backwards compatibility
   between different versions. These changes might break your application if you have used the changed part of Sulu
   in a specific way.

   In a majority of cases, the changes should not affect your project because backwards compatibility is only broken
   when it is really necessary to fix a bug. However, if something goes south, this file should contain an explanation
   what to change.

**4. Update the JavaScript build for the administration interface**

   Our administration interface requires a built version of its JavaScript code in the `public/build/admin` folder of
   the project. The JavaScript code might be adjusted between different versions to fix bugs or implement new features.
   When upgrading the project, you need to update the build to match the new Sulu version.
   To simplify this step, Sulu provides a command to update the JavaScript build in the project:

.. code-block:: bash

    $ bin/console sulu:admin:update-build

.. _Composer documentation: https://getcomposer.org/doc/articles/versions.md#writing-version-constraints
.. _sulu/skeleton repository: https://github.com/sulu/skeleton
.. _Symfony best practices: https://symfony.com/doc/current/best_practices.html
.. _changes in the sulu/skeleton repository: https://github.com/sulu/skeleton/compare/2.1.0...2.1.1
.. _UPGRADE.md file: https://github.com/sulu/sulu/blob/master/UPGRADE.md
