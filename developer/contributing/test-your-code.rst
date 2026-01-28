Test your Code
==============

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

   * ``-i`` or ``--initialize```: Initialize the test setup (e.g. creating database).
   * ``-t [BundleOrComponent]`` or ``--target [BundleOrComponent]``: Run the tests only for the specific bundle or component under packages.
   * ``-a`` or ``--all```: Run all tests.
   * ``-B`` or ``--no-bundle-tests```: Don't run the bundle tests
   * ``-C`` or ``--no-component-tests``: Don't run the component tests

Subsequently you will only need to run the tests, so you can omit the ``-i``
option.

.. code-block:: bash

    $ ./bin/runtests -a

You may also specify a specific bundle for which to run the tests:

.. code-block:: bash

    $ ./bin/runtests -C -t content # runs the packages/content component tests
    $ ./bin/runtests -C -t MediaBundle # runs the src/Sulu/Bundle/MediaBundle tests

Use PHPUnit filters to run specific tests within a bundle. For example, to run a specific test:

.. code-block:: bash

    $ ./bin/runtests -C -t MediaBundle --flags="--filter=MediaControllerTest"

Component Testing
-----------------

The component tests may be executed using the runtests script or PHPUnit from
the root directory:

.. code-block:: bash

    $ ./bin/runtests -B
    $ vendor/bin/phpunit

You can test a specific component with PHPUnit by specifying the path:

.. code-block:: bash

    $ vendor/bin/phpunit src/Sulu/Component/Cache
