Installation
============

Get the code
------------

First of all you have to clone the `sulu-standard repository on GitHub
<https://github.com/sulu-cmf/sulu-standard>`_ and change into the cloned
directory :behat:`Given I execute the following`:

.. code-block:: bash

    git clone git@github.com:sulu-cmf/sulu-standard.git

:behat:`Then the command should not fail`

After the clone has finished, you can change to the cloned directory, and
checkout the desired version of Sulu :behat:`And I execute the following`:

.. code-block:: bash

    cd sulu-standard
    git checkout 0.6.0

:behat:`Then the command should not fail`

Configure PHPCR
---------------

Before executing any other commands, you need to configure the PHPCR
implementation. 

You have to decide how you store the content - you have the choice
between:

- `Jackalope Jackrabbit`_: Uses `Apache Jackrabbit`_ as a storage backend.
- `Jackalope Doctrine-Dbal`_: Uses the a relational database as a storage backend.

To use Jackrabbit:

.. code-block:: bash

    $ cp app/config/phpcr_jackrabbit.yml.dist app/config/phpcr.yml

To use a relational database :behat:`Given I execute the following`:

.. code-block:: bash

    cp app/config/phpcr_doctrine_dbal.yml.dist app/config/phpcr.yml

:behat:`Then the command should not fail`

The copied file contains some defaults, which will run out of the box. However,
on a production system you should adjust the values ``phpcr_workspace``, 
``phpcr_user`` and ``phpcr_pass`` to some meaningful values.

.. note::

    Jackrabbit is the reference implementation of PHPCR and supports the whole
    specification, whereas the doctrine-dbal implementation does not - it is
    however more lightweight and does not have external dependencies.

Install dependencies
--------------------

We use `composer`_ to install the correct versions of
Sulu's dependencies:

You can install composer as follows :behat:`Given I execute the following`:

.. code-block:: bash

    curl -sS https://getcomposer.org/installer | php

:behat:`Then the command should not fail`
:behat:`And the file "composer.phar" should exist`

Then install the dependencies :behat:`Given I execute the following`:

.. code-block:: bash

    php composer.phar install

:behat:`Then the command should not fail`

Now check that everything is working :behat:`Given I execute the following`:

.. code-block:: bash

    php app/console

:behat:`Then the command should not fail`

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

.. _Jackalope Jackrabbit: https://github.com/jackalope/jackalope-jackrabbit
.. _Jackalope Doctrine-Dbal: https://github.com/jackalope/jackalope-doctrine-dbal
.. _Apache Jackrabbit: https://github.com/jackalope/jackalope-jackrabbit
.. _Composer:  <https://getcomposer.org/>
