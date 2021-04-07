Upgrading Sulu 2.x
==================

This upgrade guide describes how to upgrade a Sulu 2.x project to any newer version below 3.0. In a majority of cases,
these upgrades should be unproblematic, because backwards compatibility is only broken when it is really necessary to
fix a bug. The upgrade process consists of the following steps:

1. Update the sulu/sulu package
-------------------------------

The ``sulu/sulu`` package implements the functionality of the Sulu content management system. To update this package, you need to update the version constraint for the package in the ``composer.json`` of your project. 

To do this, you can replace the ``~x.x.x`` with the a version constraint like ``~2.2.7`` and execute the following
command in the root folder of your project:

.. code-block:: bash

    composer require sulu/sulu:"~x.x.x" --no-update

After this, you can update all dependencies of your project by executing the following command:

.. code-block:: bash

    composer update

.. note::

    See the `Composer documentation`_ for more information about version constraints.

2. Check sulu/skeleton repository changes
-----------------------------------------

The `sulu/skeleton repository`_ contains the project template for Sulu projects. The template might be adjusted
between different versions to include configuration for new features or keep up with the `Symfony best practices`_.
It is advised to examine the `changes in the sulu/skeleton repository`_ between the versions you are upgrading and
apply them to your project if the make sense in your case.

This step cannot be automated, because changes in sulu/skeleton repository could include BC breaks or might simply
not fit your project.

3. Check the UPGRADE.md file for BC breaks
------------------------------------------

The `UPGRADE.md file`_ in the ``sulu/sulu`` repository contains all changes breaking backwards compatibility
between different versions. These changes might break your application if you have used the changed part of Sulu
in a specific way.

In a majority of cases, the changes should not affect your project because backwards compatibility is only broken
when it is really necessary to fix a bug. However, if something goes south, this file should contain an explanation
what to change.

4. Update the Admin JavaScript build
------------------------------------

Our administration interface requires a built version of its JavaScript code in the ``public/build/admin`` folder of
the project. The JavaScript code might be adjusted between different versions to fix bugs or implement new features.
When upgrading the project, you need to update the build to match the new Sulu version.
To simplify this step, Sulu provides a command to update the JavaScript build in the project:

.. code-block:: bash

    $ bin/console sulu:admin:update-build

.. note::

    Have a look at the :doc:`../cookbook/build-admin-frontend` documentation if you want to update the 
    JavaScript build in the project without using the ``sulu:admin:update-build`` command.

.. _Composer documentation: https://getcomposer.org/doc/articles/versions.md#writing-version-constraints
.. _sulu/skeleton repository: https://github.com/sulu/skeleton
.. _Symfony best practices: https://symfony.com/doc/current/best_practices.html
.. _changes in the sulu/skeleton repository: https://github.com/sulu/skeleton/compare/2.1.0...2.1.1
.. _UPGRADE.md file: https://github.com/sulu/sulu/blob/2.x/UPGRADE.md
