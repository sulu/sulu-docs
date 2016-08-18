Getting Started
===============

Are you ready for a quick start with Sulu? Sit back, fasten your seat belts
and... go!

Bootstrap a Project
-------------------

We'll bootstrap a new project based on the `Sulu Minimal Edition`_ with
Composer_:

.. code-block:: bash

    composer create-project sulu/sulu-minimal my-project -n

This command will bootstrap a new project in the directory ``my-project``.

.. tip::

    Now is a good time to start versioning your project. If you use Git,
    initialize a new Git repository and submit your first commit:

    .. code-block:: bash

        cd my-project
        git init
        git add .
        git commit -m "Initial commit"

Webspaces
---------

The content management part of Sulu is built upon *webspaces*. Each of these
webspaces configure a content tree. Each content tree may contain translations
for different locales.

The default webspace configuration is located in
``app/Resources/webspaces/example.com.xml``. Rename this file so that it matches
the name of your project.

To get started, you only need to modify the ``<key>`` in that file. Change it
to the name of your project:

.. code-block:: xml

    <?xml version="1.0" encoding="utf-8"?>
    <webspace xmlns="http://schemas.sulu.io/webspace/webspace"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/webspace/webspace http://schemas.sulu.io/webspace/webspace-1.0.xsd">

        <name>My Project</name>
        <key>my-project</key>

        <!-- ... -->
    </webspace>

.. Caution::

    Changing the ``<key>`` of a webspace later on causes complications. We
    recommend to decide what key to use before you build the database in the
    next step.

We'll :doc:`return to webspaces <webspaces>` later in this book.

Setup the Database
------------------

Next we'll setup a database for Sulu. You can use Sulu with any of the
database backends `supported by Doctrine DBAL`_.

Once you created a database, user and password, adapt the ``database_*``
keys of your ``app/config/parameters.yml`` file. Here is an example for using
Sulu with MySQL:

.. code:: yaml

    parameters:
        database_driver: pdo_mysql
        database_host: 127.0.0.1
        database_port: null
        database_name: hellosulu
        database_user: hellosulu
        database_password: averystrongpassword
        database_version: 5.6

.. tip::

    The :doc:`parameter reference <../reference/parameters>` contains more
    information about each of the parameters in this file.

When you're done with the configuration, populate the database with Sulu's
default data:

.. code-block:: bash

    bin/adminconsole sulu:build dev

Optionally, you can store the content of your website (all tables starting with
``phpcr_``) in `Apache Jackrabbit`_. We'll get back to that later.

Start a Web Server
------------------

Now that the database is ready, we'll fire up a server to try Sulu in the browser.

Sulu is made up of two separate applications for the administration interface
and the website. Each application is optimized for its purpose. The applications
can be managed with the command line tools ``app/adminconsole`` (for the
administration) and ``app/website`` (for the website).

We'll run one server for each application:

.. code-block:: bash

    app/adminconsole server:start
    app/websiteconsole server:start

You can access the administration interface via http://127.0.0.1:8000/admin.
The default user and password is "admin".

The web frontend can be found under http://127.0.0.1:8001.

.. Tip::

    If you want to learn more about using Sulu with a real web server, read
    :doc:`../../cookbook/web-server/index`.

Next Steps
----------

Your Sulu website is ready now! Check out the administration, create pages and
play around.

When you're ready to learn more, continue with :doc:`templates`.

.. _Sulu Minimal Edition: https://github.com/sulu/sulu-minimal
.. _Composer:  https://getcomposer.org
.. _supported by Doctrine DBAL: http://doctrine-orm.readthedocs.io/projects/doctrine-dbal/en/latest/reference/platforms.html
.. _Apache Jackrabbit: http://jackrabbit.apache.org
