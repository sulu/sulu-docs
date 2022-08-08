Adding new Webspace
===================

To create a new webspace you have to create a new file within the
`config/webspaces` directory. The content of the file should be quite
similar to the `example.xml`_ file in this folder.

.. note::

    The key of the webspace has to be the same as the filename without the xml
    extension.

To activate the webspace within sulu you have to clear the cache with the command:

.. code-block:: bash

    php bin/adminconsole cache:clear
    php bin/websiteconsole cache:clear

Afterwards you will need to initialize the new webspace, to do so run the
following command:

.. code-block:: bash

    php bin/adminconsole sulu:document:initialize

.. note::

    To allow users to see the new webspace you also have to add the permissions for the
    webspace to the respective roles.

After this few steps you are able to administrate and view your new webspace.

If you have any error you can use the following command to validate your webspace:

.. code-block:: bash

    php bin/adminconsole sulu:content:validate:webspaces

.. _example.xml: https://github.com/sulu/skeleton/blob/2.x/config/webspaces/example.xml
