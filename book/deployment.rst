Deployment
==========

First time Deployment
---------------------

The first time you are deploying your application you need to set up your server and setup the application.
There are different tools to automate deployment, we will cover here the most basic one, which is using ``Git`` and ``SSH``.

1. Setup your Web Server
~~~~~~~~~~~~~~~~~~~~~~~~

You need to set up a web server; for that, have a look at :doc:`../cookbook/web-server/nginx` or :doc:`../cookbook/web-server/apache`.
You need at least ``git``, ``php`` and ``composer`` installed on your server and have a supported database (MySQL, MariaDB or PostgreSQL) running.

2. Install your Application
~~~~~~~~~~~~~~~~~~~~~~~~~~~

Create and go into the directory where you want to deploy your application and clone the repository.

.. code-block:: bash

    cd /var/www
    git clone <repository-url> example.org
    cd example.org

.. note::

    Make sure that your web server user has the right permissions to read the project data. The web server
    user also requires write permission for the ``var`` and ``public/media`` directories. Checkout the
    web server documentation for more details about how to set up permissions for your web server.

3. Configure your Project
~~~~~~~~~~~~~~~~~~~~~~~~~

Configure your application in the ``.env.local`` file or via real ENV variables if you prefer that:

.. code-block:: bash

    APP_ENV=prod
    DATABASE_URL=...

Have a look at the ``.env`` for all the available configuration options.

4. Setup your Data
~~~~~~~~~~~~~~~~~~

To setup your data you need to install the dependencies:

.. code-block:: bash

    composer install --no-dev --optimize-autoloader --classmap-authoritative

If you are not using an existing Database, you need to create your database by running the following command once
to finish setup the database, the command create also all required fixtures and search indexes:

.. code-block:: bash

    php bin/console sulu:build dev

.. note::

    This command don't need to be rerun on every deployment, check Continuous Deployment section below for more details
    about which commands are required for every deployment.

5. Check Login and Website
~~~~~~~~~~~~~~~~~~~~~~~~~~

After it you should be able to login to the admin interface and see your website.

.. note::

    Always have a look at the `Symfony Performance Best Practices`_ to make sure your project is optimized for production.

Continuous Deployment
---------------------

For any further deployment you just need to get the latest changes from your repository:

.. code-block:: bash

    git pull ...
    # or
    git fetch ...
    git checkout ...

If your ``composer.lock`` changed you need to run the composer install command again:

.. code-block:: bash

    composer install --no-dev --optimize-autoloader --classmap-authoritative

If any services, config or twig files changed you need to clear the caches:

.. code-block:: bash

    php bin/console cache:clear
    php bin/websiteconsole cache:clear

If you have external caches in Redis or somewhere else you need to clear them via the related commands.

For database changes for example during Sulu upgrades you need to run the related database migration commands:

.. code-block:: bash

    php bin/console phpcr:migrations:migrate

We recommend the usage of `Doctrine Migration Bundle`_ for your own database changes, so you can run following command:

.. code-block:: bash

    php bin/console doctrine:migrations:migrate
    # alternative but not recommended is using built-in schema update command:
    php bin/console doctrine:schema:update --dump-sql # check changes
    php bin/console doctrine:schema:update --force # run changes

This should just give a raw overview of a simple deployment process,
there are many tools to automate this process and make it more robust,
we recommend using tools like `Deployer`_ to automate this process.

.. note::

    Keep in mind that PHP has a OPCache which may need be cleared after deployment by restarting your PHP-FPM or Apache service.
    Or use a tool like `gordalina cachetool`_ to clear the cache without restarting the service.

.. note::

    Check always the `Sulu UPGRADE`_ documentation for any required changes during the upgrade process.

.. _Symfony Performance Best Practices: https://symfony.com/doc/current/performance.html
.. _Doctrine Migration Bundle: https://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html
.. _Deployer: https://deployer.org/
.. _gordalina cachetool: https://github.com/gordalina/cachetool
.. _Sulu UPGRADE: https://github.com/sulu/sulu/blob/2.6/UPGRADE.md
