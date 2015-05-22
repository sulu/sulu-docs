Installation
============

Get the code
------------

First of all you have to clone the `sulu-standard repository on GitHub
<https://github.com/sulu-io/sulu-standard>`_ and change into the cloned
directory.

.. code-block:: bash

    $ git clone git@github.com:sulu-io/sulu-standard.git

After the clone has finished, you can change to the cloned directory, and
checkout the desired version of Sulu:

.. code-block:: bash

    $ cd sulu-standard
    $ git checkout 0.9.0

Install dependencies
--------------------

We use `composer`_ to install the correct versions of
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
    * - sulu_admin.email
      - Administrator email address
    * - content_fallback_intervall
      - The intervall in milliseconds, between content preview update in the
        http polling mode
    * - websocket_port
      - The port which will be used for the content preview in the http polling mode
    * - websocket_url
      - The url which will be used for the content preview in the http polling mode        
    * - phpcr_backend
      - The PHPCR backend definition, defaults to the doctrine-dbal, check
        http://doctrine-phpcr-odm.readthedocs.org/en/latest/reference/installation-configuration.html
        for more configuration options
    * - phpcr_workspace
      - The PHPCR workspace which will be used
    * - phpcr_user
      - The user for phpcr
    * - phpcr_pass
      - The password for phpcr
    * - phpcr_cache
      - PHPCR caching type

.. _Jackalope Jackrabbit: https://github.com/jackalope/jackalope-jackrabbit
.. _Jackalope Doctrine-Dbal: https://github.com/jackalope/jackalope-doctrine-dbal
.. _Apache Jackrabbit: https://github.com/jackalope/jackalope-jackrabbit
.. _Composer:  https://getcomposer.org/
.. _MassiveBuildBundle: http://github.com/massiveart/MassiveBuildBundle
