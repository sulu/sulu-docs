Maintenance Mode
================

When you need to deploy a new version of your project on a production environment
it is often necessary to disable your sulu-application and inform your users
about it.

Sulu maintenance mode displays a simple holding page which can be easily customized.

Activate Maintenance Mode
-------------------------

Sulu is shipped with a simple maintenance page stored in `public/maintenance.php`_
file which can be changed for your needs.

To activate the maintenance mode you need to  set the environment variable SULU_MAINTENANCE to true.
For example, in your ``.htaccess`` file or vhost file for apache:

.. code-block:: apache

    SetEnv SULU_MAINTENANCE true

For nginx you can configure the maintenance mode in the php part of your vhost by adding:

.. code-block:: nginx

    fastcgi_param SULU_MAINTENANCE true;

Configure Maintenance Mode
--------------------------

Allowed IP addresses
~~~~~~~~~~~~~~~~~~~~

You may like to access your application while maintenance mode is active. Then you need to set the allowed IPs:

.. code-block:: php

    <?php
    $allowedIPs = ['127.0.0.1'];

Translations
~~~~~~~~~~~~

You can define translations for your template as follows:

.. code-block:: php

    <?php
    $translations = [
       'en' => [
          'title' => 'Maintenance',
          'heading' => 'The page is currently down for maintenance',
          'description' => 'Sorry for any inconvenience caused. Please try again shortly.',
       ],
    ];

Default locale
~~~~~~~~~~~~~~

By default, ``maintenance.php`` is automatically detecting your browsers language. If no translation for this language
exists the default locale is being used. By default this is English:

.. code-block:: php

    <?php
    define('DEFAULT_LOCALE', 'en');

.. _public/maintenance.php: https://github.com/sulu/skeleton/blob/master/public/maintenance.php
