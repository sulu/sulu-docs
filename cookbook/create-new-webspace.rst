Adding new Webspace
===================

To create a new webspace you have to create a new file within the
`app/Resources/webspaces` directory. The content of the file should be quite
similar to the `sulu_io.xml.dist` file in this folder.

.. note::

    The key of the webspace has to be the same as the filename without the xml
    extension.

To activate the webspace within sulu with a `prod` or `stage` environment
you have to clear the cache with the command:
`app/console clear:cache -e <environment>`

To initiate the database for the new webspace run the command
`app/console sulu:phpcr:init && app/console sulu:webspaces:init`.

To allow users to see the new webspace you also have to set the permissions in
the roles for the new webspace.

After this few steps you are able to administrate and view your new webspace.
