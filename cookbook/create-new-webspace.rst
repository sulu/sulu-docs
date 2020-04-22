Adding new Webspace
===================

To create a new webspace you have to create a new file within the
`app/Resources/webspaces` directory. The content of the file should be quite
similar to the `example.com.xml`_ file in this folder.

.. note::

    The key of the webspace has to be the same as the filename without the xml
    extension.

To activate the webspace within sulu with a `prod` or `stage` environment
you have to clear the cache with the command:

.. code-block:: bash

    $ php bin/adminconsole cache:clear -e <environment>

Afterwards you will need to initialize the new webspace, to do so run the
following command:

.. code-block:: bash

    $ php bin/adminconsole sulu:document:initialize

.. note::

    To allow users to see the new webspace you also have to add the permissions for the
    webspace to the respective roles.

After this few steps you are able to administrate and view your new webspace.

If you have any error you can use the following command to validate your webspace:

.. code-block:: bash

     $ php bin/adminconsole sulu:content:validate:webspaces

.. _example.com.xml: https://github.com/sulu/sulu-minimal/blob/1.6.22/app/Resources/webspaces/example.com.xml
