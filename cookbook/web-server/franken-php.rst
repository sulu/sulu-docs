FrankenPHP via Docker
=====================

To add FrankenPHP to an existing project you can follow these steps: `FrankenPHP Documentation`_.

However after running through those steps you need to modify the setup a slight bit:
1. Installing the correct php extensions
2. Adding a database to the project
3. Removing Sulu's default configuration

The modifications
-----------------

.. note::

    Beware these instructions might get out of date if FrankenPHP Documentation changes.

After installing you should have a `Dockerfile` which is the base for all containers.

There should be a section under like this:

.. code-block:: dockerfile

    # Base FrankenPHP image
    ...
    RUN set -eux; \
        install-php-extensions \
            @composer \
            apcu \
            intl \
            opcache \
            zip \
        ;

This is the list of installed extensions. For Sulu you also need: `gd` and `pdo_mysql` or `pdo_postgres`.

Secondly we also need to add a database. We can just do that by adding it to the yaml configuration file `compose.yaml`.

Here is an example configuration for mysql:

.. code-block:: yaml

       services:
          # ....
          database:
            # arm compatible mysql docker image
            image: mysql/mysql-server:${MYSQL_VERSION:-8.0} # arm and x86/x64 compatible mysql image
            command: [ "--max_connections=1000", "--character-set-server=utf8mb4", "--collation-server=utf8mb4_general_ci" ]
            environment:
              MYSQL_DATABASE: ${MYSQL_DATABASE:-sulu}
              # You should definitely change the password in production
              MYSQL_ROOT_PASSWORD: ${MYSQL_ROOT_PASSWORD:-ChangeMe}
              MYSQL_ROOT_HOST: '%'
            volumes:
              - db-data:/var/lib/mysql
              # You may use a bind-mounted host directory instead, so that it is harder to accidentally remove the volume and lose all your data!
              # - ./docker/db/data:/var/lib/mysql:rw

After setting up the container you might also want to update the `DATABASE_URL` variable in the php service (by default it assumes postgres). At least note that FrankenPHP and the default Sulu mysql container have different default credentials.

If you have some other modifications in your set up be sure to copy all changes of the `docker-compose*` files to the `compose.yaml` and `compose.override.yaml`.

After all this, the Sulu default configuration is not used anymore and can be removed:

.. code-block:: bash

    rm docker-compose.yaml
    rm docker-compose.override.yaml

.. _FrankenPHP Documentation: https://github.com/dunglas/symfony-docker/blob/main/docs/existing-project.md#installing-on-an-existing-project
