Getting Started
===============

Are you ready for a quick start with Sulu? Sit back, fasten your seat belts
and... go!

Bootstrap a Project
-------------------

We'll bootstrap a new project based on the `Sulu Skeleton`_ with
Composer_:

.. code-block:: bash

    composer create-project sulu/skeleton my-project -n

This command will bootstrap a new project in the directory ``my-project``.

.. tip::

    Now is a good time to start versioning your project. If you use Git,
    initialize a new Git repository and submit your first commit:

    .. code-block:: bash

        cd my-project
        git init
        git add .
        git commit -m "Initial commit"

.. note::

    If you want to use other languages than english or german for the
    administration interface of Sulu you can install them using the following
    command:

    .. code-block:: bash

        bin/console sulu:admin:download-language <language>

    Available languages are shown on `Crowdin`_ and you have to replace
    <language> with the two letter code shown there.

Webspaces
---------

The content management part of Sulu is built upon *webspaces*. Each of these
webspaces configure a content tree. Each content tree may contain translations
for different locales.

The default webspace configuration is located in
``config/webspaces/example.xml``. Rename this file so that it matches
the name of your project.

To get started, change the ``<name>`` and the ``<key>`` of the webspace to the
name of your project. The name is a human-readable label that is shown in the
administration interface. The key is the unique identifier of the webspace:

.. code-block:: xml

    <?xml version="1.0" encoding="utf-8"?>
    <webspace xmlns="http://schemas.sulu.io/webspace/webspace"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/webspace/webspace http://schemas.sulu.io/webspace/webspace-1.1.xsd">

        <name>My Project</name>
        <key>my-project</key>

        <!-- ... -->
    </webspace>

.. caution::

    Changing the ``<key>`` of a webspace later on causes complications. We
    recommend to decide what key to use before you build the database in the
    next step.

We'll :doc:`return to webspaces <webspaces>` later in this book.

Setup the Database
------------------

Next we'll setup a database for Sulu. You can use Sulu with the database
backends `supported by Doctrine DBAL`_. Some of those are currently still
untested:

+------------------------------+---------------------------------------+
| Platform                     | Supported                             |
+==============================+=======================================+
| MySQL                        | yes                                   |
+------------------------------+---------------------------------------+
| PostgreSQL                   | yes                                   |
+------------------------------+---------------------------------------+
| Oracle                       | untested                              |
+------------------------------+---------------------------------------+
| Microsoft SQL Server         | untested                              |
+------------------------------+---------------------------------------+
| SAP Sybase SQL Anywhere      | untested                              |
+------------------------------+---------------------------------------+
| SQLite                       | no                                    |
+------------------------------+---------------------------------------+

The database connection information is stored as an environment variable called ``DATABASE_URL``.
For development, you can find and customize this inside ``.env.local``:
Here is an example for using Sulu with MySQL:

.. code:: bash

    DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name

When you're done with the configuration, populate the database with Sulu's
default data:

.. code-block:: bash

    bin/adminconsole sulu:build dev

.. caution::

    This command adds a user "admin" with password "admin" to your installation!
    If you don't want to add that user, pass the argument ``prod`` instead:

    .. code-block:: bash

        bin/adminconsole sulu:build prod

Optionally, you can store the content of your website (all tables starting with
``phpcr_``) in `Apache Jackrabbit`_. We'll get back to that later.

Start a Web Server
------------------

Now that the database is ready, we'll fire up a server to try Sulu in the browser.

Sulu is made up of two separate applications for the administration interface
and the website. Each application is optimized for its purpose. The applications
can be managed with the command line tools ``bin/adminconsole`` (for the
administration) and ``bin/websiteconsole`` (for the website).

However, we will run one server for both applications, and our front controller
will make sure the correct application is loaded.

.. code-block:: bash

    bin/console server:start

You can access the administration interface via http://127.0.0.1:8000/admin.
The default user and password is "admin".

The web frontend can be found under http://127.0.0.1:8000.

.. tip::

    If you want to learn more about using Sulu with a real web server, read
    :doc:`../../cookbook/web-server/index`.

Next Steps
----------

Your Sulu website is ready now! Check out the administration, create pages and
play around.

When you're ready to learn more, continue with :doc:`templates`.

.. _Sulu Skeleton: https://github.com/sulu/skeleton
.. _Composer: https://getcomposer.org
.. _supported by Doctrine DBAL: http://doctrine-orm.readthedocs.io/projects/doctrine-dbal/en/latest/reference/platforms.html
.. _Apache Jackrabbit: http://jackrabbit.apache.org
.. _Crowdin: https://sulu.crowdin.com/sulusulu
