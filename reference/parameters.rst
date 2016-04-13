Parameter Reference
===================

You can customize your Sulu installation by changing parameter values of two files:

* |app/config/parameters.yml|_
* |app/config/phpcr.yml|_

This guide documents each of the keys in these files.

app/config/parameters.yml
-------------------------

=================== ============================================================
Parameter           Description
=================== ============================================================
database_driver     Defines which database driver will be used
database_host       The address of the server that is running the database
database_port       The port used to access the database on the server
database_name       The name of the database
database_user       The name of the database user
database_password   The password of the database user
mailer_transport    The protocol to send mails
mailer_host         The server from which the mails will be sent
mailer_user         The username for sending mails
mailer_password     The password for sending mails
locale              The default locale for the system
secret              An unique key needed by the symfony framework
sulu_admin.name     A name, which will be shown in the administration interface
sulu_admin.email    Administrator email address
websocket_port      The port which will be used for the content preview in the
                    HTTP polling mode
websocket_url       The URL which will be used for the content preview in the
                    HTTP polling mode
=================== ============================================================

app/config/phpcr.yml
--------------------

=================== ============================================================
Parameter           Description
=================== ============================================================
phpcr_backend       The PHPCR backend definition, defaults to the doctrine-dbal,
                    check `the PHPCR documentation`_ for more configuration
                    options
phpcr_workspace     The PHPCR workspace which will be used
phpcr_user          The user for phpcr
phpcr_pass          The password for phpcr
phpcr_cache         PHPCR caching type
=================== ============================================================

.. |app/config/parameters.yml| replace:: ``app/config/parameters.yml``
.. |app/config/phpcr.yml| replace:: ``app/config/phpcr.yml``
.. _the PHPCR documentation: http://doctrine-phpcr-odm.readthedocs.org/en/latest/reference/installation-configuration.html
