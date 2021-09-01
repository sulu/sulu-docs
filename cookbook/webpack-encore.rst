Using Webpack Encore for your website assets
============================================

Webpack Encore is a tool to manage your javascript and css assets. It
seamlessly integrates webpack into your symfony application.

Installation
------------

First add the `WebpackEncoreBundle`_ as a dependency to your
composer.json file.

.. code:: bash

   composer require symfony/webpack-encore-bundle

To enable it, add the following line to the ``config/bundles.php`` file,
if that has not been done already for you by Symfony Flex:

.. code:: php

   return [
       ...
       Symfony\WebpackEncoreBundle\WebpackEncoreBundle::class => ['all' => true],
   ];

Configuration
-------------

There are several files and directories added to your project by Symfony
Flex:

* ``assets/controllers/``
* ``assets/styles/``
* ``assets/app.js``
* ``assets/bootstrap.js``
* ``assets/controllers.json``
* ``assets/controllers/hello_controller.js``
* ``assets/styles/app.css``
* ``config/packages/assets.yaml``
* ``config/packages/prod/webpack_encore.yaml``
* ``config/packages/test/webpack_encore.yaml``
* ``config/packages/webpack_encore.yaml``
* ``package.json``
* ``webpack.config.js``

In a normal Symfony application, that would already work fine, but to
use Webpack Encore with Sulu, some configuration has to be adjusted,
because there is an additional Javascript application for Sulu’s admin interface.

To continue, create a directory ``assets/website/`` and move the
newly added files and directories from ``assets/`` into ``assets/website``.

Next, add the following changes to ``webpack.config.js``:

.. code:: diff

    Encore
        // directory where compiled assets will be stored
   -    .setOutputPath('public/build/')
   +    .setOutputPath('public/build/website/')
        // public path used by the web server to access the output path
   -    .setPublicPath('/build')
   +    .setPublicPath('/build/website')
        // only needed for CDN's or sub-directory deploy
        //.setManifestKeyPrefix('build/')

        /*
         * ENTRY CONFIG
         *
         * Add 1 entry for each "page" of your app
         * (including one that's included on every page - e.g. "app")
         *
         * Each entry will result in one JavaScript file (e.g. app.js)
         * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
         */
   -    .addEntry('app', './assets/app.js')
   +    .addEntry('app', './assets/website/app.js')

        // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
   -    .enableStimulusBridge('./assets/controllers.json')
   +    .enableStimulusBridge('./assets/website/controllers.json')

Because of the above changes, you also have to change the following
configuration files:

* ``config/packages/assets.yaml``

.. code:: diff

    framework:
        assets:
   -        json_manifest_path: '%kernel.project_dir%/public/build/manifest.json'
   +        json_manifest_path: '%kernel.project_dir%/public/build/website/manifest.json'

* and ``config/packages/webpack_encore.yaml``

.. code:: diff

    webpack_encore:
        # The path where Encore is building the assets - i.e. Encore.setOutputPath()
   -    output_path: '%kernel.project_dir%/public/build'
   +    output_path: '%kernel.project_dir%/public/build/website'
        # If multiple builds are defined (as shown below), you can disable the default build:
        # output_path: false

Luckily, Webpack Encore provides a deadly simple way to link to your
built assets inside your html. Therefore, change the following lines in
your base template at ``templates/base.html.twig``:

.. code:: diff

    <head>
   -    {% block style %}{% endblock %}
   +    {% block style %}
   +        {{ encore_entry_link_tags('app') }}
   +    {% endblock %}
    </head>

    <body>
   -    {% block javascripts %}{% endblock %}
   +    {% block javascripts %}
   +        {{ encore_entry_script_tags('app') }}
   +    {% endblock %}
    </body>

And that’s it!

Build
-----

Now you are ready to add your scripts and styles in ``assets/website``.
When you finished your changes, open your terminal in the root directory
and run the following command:

.. code:: bash

   npm install
   npm run build

Now you should be able to see the outcome in the browser.

Optional: Install Web-JS
------------------------

In order to install the UI-Library ``web-js`` you have to remove the ``stimulus``
library from the generated files.

Remove the following files / directories:

* ``assets/website/bootstrap.js``
* ``assets/website/controllers/``
* ``assets/website/controllers.json``

And remove following lines from ``assets/website/app.js``:

.. code:: diff

    - // start the Stimulus application
    - import './bootstrap';

And comment out the following line in ``webpack.config.js``:

.. code:: diff

        // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
   -    .enableStimulusBridge('./assets/website/controllers.json')
   +    // .enableStimulusBridge('./assets/website/controllers.json')

After that you are able to install ``web-js`` via the documentation
of the `web-js repository`_.

Customization
-------------

For further customization of your frontend setup, follow the `Webpack
Encore Documentation`_.

.. _WebpackEncoreBundle: https://github.com/symfony/webpack-encore-bundle
.. _Webpack Encore Documentation: https://symfony.com/doc/current/frontend.html#webpack-encore
.. _web-js repository: https://github.com/sulu/web-js
