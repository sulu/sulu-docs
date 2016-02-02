Test your Code
==============

Sulu has three types of tests: Unit, Functional and Sceneario (i.e. "Behat").

If your tests require external dependencies (e.g. a database connection) then
they are Functional tests, otherwise they will be a Unit Test.

One key quality of Unit tests is that they execute very fast, whereas
functional tests tend to be slower.

Functional Tests
----------------

To run the tests, follow these steps:

1. Install Composer dependences with ``composer install``.

2. Run ``./bin/jackrabbit.sh``. This will install a local copy of Jackrabbit into the ``./bin`` directory and start the
   server. If you already have a running server on your machine, you can skip this step. Also if you want the tests to run on
   different port than the default `8080`, you need to install Jackrabbit on your own.

3. Run the tests in one of the following ways.

Bundle Testing
~~~~~~~~~~~~~~

The test runner script is a bash script which automates the execution of
**Bundle** tests. It is used by the continuous integration server and can be
useful to quickly get the tests running.

.. note::

    This script is only used for the bundles (located in the ``Sulu\\\\Bundle`` namespace). If you are programming a component see below.

.. code-block:: bash

    $ ./bin/runtests.sh -ia

The above will initialize the database(`i`) and run all the tests (`a`)

`runtests.sh` has the following options:

   * ``-i``: Initialize the test setup (e.g. creating database).
   * ``-t [Bundle]``: Run the tests only for the specific bundle.
   * ``-a``: Run all tests.

Subsequently you will only need to run the tests, so you can ommit the `-i`
option.

.. code-block:: bash

    $ ./bin/runtests.sh -a

You may also specify a specific bundle for which to run the tests:

.. code-block:: bash

    $ ./bin/runtests.sh SuluSearchBundle

After the bundles have been initialized you may also simply change to the
bundle root directory and use ``phpunit`` as normal:

.. code-block:: bash

    $ cd src/Sulu/Bundle/SuluSearchBundle
    $ phpunit

Component Testing
-----------------

The component tests may be executed using PHPUnit from the root directory:

.. code-block:: php

    $ phpunit

You can specify a specific component by specifying the path:

.. code-block:: php

    $ phpunit src/Sulu/Component/Content

Test Environment Configuration
------------------------------

Sulu uses the ``SuluTestBundle`` to simplify testing. This bundle also takes care of configuration for your test
environment. For example, if you changed the port for your Jackrabbit server to ``8888``, you can use environment variables
to let Symfony override default parameters:

.. code-block:: bash

    $ SYMFONY__PHPCR_BACKEND_URL=http://localhost:8888/server/ ./bin/runtests.sh

More information in the `Symfony docs`_. For a list of available parameters take a look into the `parameter.yml`_.

.. _Symfony docs: http://symfony.com/doc/current/cookbook/configuration/external_parameters.html
.. _parameter.yml: https://github.com/sulu-io/sulu/tree/develop/src/Sulu/Bundle/TestBundle/Resources/dist/parameter.yml
