Adding new Webspace
===================

To create a new webspace you have to create a new file within the
`config/webspaces` directory. The content of the file should be quite
similar to the `example.xml`_ file in this folder.

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

To allow users to see the new webspace you also have to set the permissions in
the roles for the new webspace.

After this few steps you are able to administrate and view your new webspace.

If you have any error you can use the following command to validate your webspace:

    $ php bin/adminconsole sulu:content:validate:webspaces

.. _example.xml: https://github.com/sulu/skeleton/blob/master/config/webspaces/example.xml
