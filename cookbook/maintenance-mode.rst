Sulu Maintenance Mode
=====================

When you need to deploy a new version of your project on a production environment
it is often necessary to disable your sulu-application and inform your users
about it.

Sulu maintenance mode displays a simple holding page which can be easily customized.

Create Maintenance Mode
-----------------------

To create a maintenance page, you first need to create a ``maintenance.php`` file:

.. code-block:: bash

    $ cp app/maintenance.php.dist app/maintenance.php

Then you need to set the environment variable SULU_MAINTENANCE to true.
For example, in your ``.htaccess`` file (for apache)
 
.. code-block:: apache

    SetEnv SULU_MAINTENANCE true

Configure Maintenance Mode
--------------------------

Allowed IP addresses
~~~~~~~~~~~~~~~~~~~~

You may like to access your application while maintenance mode is active. Then you need to set the allowed IPs:

.. code-block:: php

    <?php
    $allowedIPs = array(
        '127.0.0.1'
    );

Translations
~~~~~~~~~~~~

You can define translations for your template as follows:

.. code-block:: php

    <?php
    $translations = array(
        'en' => array(
            'title' => 'Maintenance',
            'heading' => 'The page is currently down for maintenance',
            'description' => 'Sorry for any inconvenience caused. Please try again shortly.',
        ),
    );

Default locale
~~~~~~~~~~~~~~

By default, ``maintenance.php`` is automatically detecting your browsers language. If no translation for this language 
exists the default locale is being used. By default this is English:

.. code-block:: php

    <?php
    define('DEFAULT_LOCALE', 'en');
