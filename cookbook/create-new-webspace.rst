Adding new Webspace
===================

To create a new webspace you have to create a new file within the
`config/webspaces` directory. The content of the file should be quite
similar to the `website.xml`_ file in this folder.

.. note::

    The key of the webspace has to be the same as the filename without the xml
    extension.

To activate the webspace within sulu you have to clear the cache with the command:

.. code-block:: bash

    php bin/console cache:clear

Afterwards you will need to initialize the new webspace, to do so run the
following command:

.. code-block:: bash

    php bin/console sulu:page:initialize

.. note::

    To allow users to see the new webspace you also have to add the permissions for the
    webspace to the respective roles.

After this few steps you are able to administrate and view your new webspace.

.. _website.xml: https://github.com/sulu/skeleton/blob/3.x/config/webspaces/website.xml
