Installation
============

Get the code
------------

First of all you have to clone the `sulu-standard repository on GitHub <https://github.com/sulu-cmf/sulu-standard>`_ and change into the cloned directory.

.. code-block:: bash

    git clone git@github.com:sulu-cmf/sulu-standard.git

After the clone has finished, you can change to the cloned directory, and
checkout the desired version of Sulu:

.. code-block:: bash

    cd sulu-standard
    git checkout 0.6.0

Configure PHPCR
---------------

Before executing any other commands, you should configure PHPCR. Therfore you
have to decide where you want to store the content. You have the choice between 
`Apache Jackrabbit <http://jackrabbit.apache.org/>`_ and a common relational 
database. Depending on your decision you have to copy one of the two phpcr 
configuration files.

Either you copy the file for Jackrabbit:

.. code-block:: bash

    cp app/config/phpcr_jackrabbit.yml.dist app/config/phpcr.yml

or the file for doctrine dbal:

.. code-block:: bash

    cp app/config/phpcr_doctrine_dbal.yml.dist app/config/phpcr.yml

The copied file contains some defaults, which will run out of the box. However,
on a production system you should adjust the values ``phpcr_workspace``, 
``phpcr_user`` and ``phpcr_pass`` to some meaningful values.

Install dependencies
--------------------

We use `composer <https://getcomposer.org/>`_ to load the correct versions of
Sulu's dependencies:

.. code-block:: bash

    composer install

At the end of the installation you will be asked for some parameters. The
following table describes these parameters, whereby most of the default values
should be fine for simple installations.

.. list-table::
    :header-rows: 1

    * - Parameter
      - Description
    * - database_driver
      - Defines which database driver will be used
    * - database_host
      - The address of the server, on whch the database is running
    * - database_port
      - The port number to access the database on that server
    * - database_name
      - The name of the database
    * - database_user
      - The name of the database user
    * - database_password
      - The password of the database user
    * - mailer_transport
      - The protocol to send mails (currently not used)
    * - mailer_host
      - The server from which the mails will be sent (currently not used)
    * - mailer_user
      - The username for sending mails (currently not used)
    * - mailer_password
      - The password for sending mails (currently not used)
    * - locale
      - The default locale for the system
    * - secret
      - An unique key needed by the symfony framework
    * - sulu_admin.name
      - A name, which will be shown in the administration interface
    * - content_fallback_intervall
      - The intervall in milliseconds, between content preview update in the
        http polling mode
    * - content_preview_port
      - The port which will be used for the content preview in the http polling
        mode

