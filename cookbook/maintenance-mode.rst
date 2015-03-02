Sulu Maintenance Mode
====================

When you need to deploy a new version of your project on a production environment
it is often necessary to disable your sulu-application and inform your customers
 about it.

Sulu maintenance mode displays a simple error page which can be easily customized.

Create Maintenance Mode
-----------------------

To create a maintenance page, you first need to create a maintenance.php file:

.. code-block:: bash

    $ cp app/maintenance.php.dist app/maintenance.php

Then you need to set the env variable SULU_MAINTENANCE to true. 
E.g.: in your .htaccess file (for apache)
 
.. code-block:: config

    SetEnv SULU_MAINTENANCE true

Configure Maintenance Mode
--------------------------

IP-Addreses:
~~~~~~~~~~~~~

You may like to access your application while maintenance mode is active. Then you need to set $allowedIPs:

.. code-block:: php

    $allowedIPs = array(
        '127.0.0.1'
    );

Translations:
~~~~~~~~~~~~~
You can define translations for your template as follows:

.. code-block:: php

    $translations = array(
        'en' => array(
            'title' => 'Maintenance',
            'heading' => 'The page is currently down for maintenance',
            'description' => 'Sorry for any inconvenience caused. Please try again shortly.',
        ),
    );
    
Default locale:
~~~~~~~~~~~~~~

By default, maintenance.php is automatically detecting your browsers language. If no translation for this language exists
the default locale is being used. By default this is english:

.. code-block:: php

    define('DEFAULT_LOCALE', 'en');
    