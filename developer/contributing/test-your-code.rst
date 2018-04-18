Test your Code
==============

Sulu has three types of tests: Unit, Functional and Scenario (i.e. "Behat").

If your tests require external dependencies (e.g. a database connection) then
they are Functional tests, otherwise they will be a Unit Test.

One key quality of Unit tests is that they execute very fast, whereas
functional tests tend to be slower.

Functional Tests
----------------

To run the tests, follow these steps:

1. Install Composer dependencies with ``composer install``.

2. Run the tests in one of the following ways.

Bundle Testing
~~~~~~~~~~~~~~

The test runner script is a php script which automates the execution of
**Bundle** tests. It is used by the continuous integration server and can be
useful to quickly get the tests running.

.. code-block:: bash

    $ ./bin/runtests -i -C

The above will initialize the database(``i``) and run all the tests excluding
the component tests (``C``).

`runtests` has the following options:

   * ``-i``: Initialize the test setup (e.g. creating database).
   * ``-t [Bundle]``: Run the tests only for the specific bundle.
   * ``-a``: Run all tests.
   * ``-B``: Don't run the bundle tests
   * ``-C``: Don't run the component tests
   * ``-r``: Restart jackrabbit between each bundle when running all tests.

Subsequently you will only need to run the tests, so you can omit the ``-i``
option.

.. code-block:: bash

    $ ./bin/runtests -a

You may also specify a specific bundle for which to run the tests:

.. code-block:: bash

    $ ./bin/runtests -C -t SuluSearchBundle

After the bundles have been initialized you may also simply change to the
bundle root directory and use ``phpunit`` as normal:

.. code-block:: bash

    $ cd src/Sulu/Bundle/SuluSearchBundle
    $ phpunit

Component Testing
-----------------

The component tests may be executed using the runtests script or PHPUnit from
the root directory:

.. code-block:: bash

    $ ./bin/runtests -B
    $ phpunit

You can test a specific component with PHPUnit by specifying the path:

.. code-block:: bash

    $ phpunit src/Sulu/Component/Content

Jackrabbit installation
-----------------------

By default Sulu uses the Doctrine DBAL implementation for PHPCR in your local
test environment. If you need to test against the Jackrabbit backend, you can
install it with the following bash snippet:

.. code-block:: bash

    JACKRABBIT_VERSION=2.12.0
    if [ ! -f downloads/jackrabbit-standalone-$JACKRABBIT_VERSION.jar ]; then
        cd downloads
        wget http://archive.apache.org/dist/jackrabbit/$JACKRABBIT_VERSION/jackrabbit-standalone-$JACKRABBIT_VERSION.jar
        cd -
    fi

To start your jackrabbit installation run

.. code-block:: bash

    java -jar downloads/jackrabbit-standalone-2.12.0.jar > /dev/null &

Now you have to run your tests with the ``jackrabbit`` backend enabled (omit the
initialization step [``-i``] after the first run):

.. code-block:: bash

    $ SYMFONY__PHPCR__TRANSPORT=jackrabbit ./bin/runtests -i -a


Test Environment Configuration
------------------------------

Sulu uses the ``SuluTestBundle`` to simplify testing. This bundle also takes care of configuration for your test
environment. For example, if you changed the port for your Jackrabbit server to ``8888``, you can use environment variables
to let Symfony override default parameters:

.. code-block:: bash

    $ SYMFONY__PHPCR__TRANSPORT=jackrabbit SYMFONY__PHPCR__BACKEND_URL=http://localhost:8888/server/ ./bin/runtests -a

More information in the `Symfony docs`_. For a list of available parameters take a look into the `parameter.yml`_.

.. _Symfony docs: http://symfony.com/doc/current/cookbook/configuration/external_parameters.html
.. _parameter.yml: https://github.com/sulu/sulu/tree/develop/src/Sulu/Bundle/TestBundle/Resources/dist/parameter.yml
