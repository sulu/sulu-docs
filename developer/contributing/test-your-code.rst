Test your Code
=======================

For running the tests, follow these steps:

1. Install Composer dependences with ``composer install``.

2. Run ``./bin/jackrabbit.sh``. This will install a local copy of Jackrabbit into the ``./bin`` directory and start the
   server. If you already have a running server on your machine, you can skip this step. Also if you want the tests to run on
   different port than the default `8080`, you need to install Jackrabbit on your own.

3. Run the tests with ``./bin/runtests.sh``. This command has two possible options:

   * ``-i``: Initialize the database and run all tests.
   * ``-t [Bundle]``: Run the tests only for the specific bundle.

Configure tests
-----------------------

Sulu uses the ``SuluTestBundle`` to simplify testing. This bundle also takes care of configuration for your test
environment. For example, if you changed the port for your Jackrabbit server to ``8888``, you can use environment variables
to let Symfony override default parameters:

.. code-block:: bash

    $ SYMFONY__PHPCR_BACKEND_URL=http://localhost:8888/server/ ./bin/runtests.sh

More information in the `Symfony docs`_. For a list of available parameters take a look into the `parameter.yml`_.

.. _Symfony docs: http://symfony.com/doc/current/cookbook/configuration/external_parameters.html
.. _parameter.yml: https://github.com/sulu-io/sulu/tree/develop/src/Sulu/Bundle/TestBundle/Resources/dist/parameter.yml