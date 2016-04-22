Installation
============

After working through this page you'll have a running Sulu instance. At first we'll
load Sulu and afterwards dependent packages.

Get the code
------------

First of all you have to clone the `sulu-standard repository on GitHub
<https://github.com/sulu/sulu-standard>`_ and change into the cloned
directory.

.. code-block:: bash

    $ git clone https://github.com/sulu/sulu-standard.git

After the clone has finished, you can change to the cloned directory, and
checkout the latest version of Sulu:

.. code-block:: bash

    $ cd sulu-standard
    $ git checkout master

Install dependencies
--------------------

Use `Composer`_ to install Sulu's dependencies:

.. code-block:: bash

    composer install

At the end of the installation, Composer asks you to submit values of different
parameters. For now, just press "Enter" to keep their default values. You can
learn more about each parameter in the :doc:`../../reference/parameters`.

Congratulations! You have just installed Sulu. Continue with :doc:`setup` to
setup your first Sulu website.

.. _Jackalope Jackrabbit: https://github.com/jackalope/jackalope-jackrabbit
.. _Jackalope Doctrine-Dbal: https://github.com/jackalope/jackalope-doctrine-dbal
.. _Apache Jackrabbit: https://github.com/jackalope/jackalope-jackrabbit
.. _Composer:  https://getcomposer.org/
.. _MassiveBuildBundle: http://github.com/massiveart/MassiveBuildBundle
