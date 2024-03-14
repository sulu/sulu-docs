Cleanup PHPCR database
======================

When you have an older installation of Sulu, you might have many old PHPCR properties in your database. Especially if
you have changed you templates or content types a lot. To clean up the database you can use the following command:

.. code-block:: bash

    php bin/console sulu:phpcr:cleanup

The command is quite powerfull but also risky to run it in production. It will remove all properties which are not used
in the current templates. So make sure to have a backup of your database before running this command.
