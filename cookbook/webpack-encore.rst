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

There are several files added to your project by Symfony Flex:

* ``assets/app.js``
* ``assets/styles/app.css``
* ``config/packages/webpack_encore.yaml``
* ``package.json``
* ``webpack.config.js``

In a normal Symfony application, that would already work fine, but to
use Webpack Encore with Sulu, some configuration has to be adjusted,
because there is an additional Javascript application for Sulu’s admin interface.

To continue, create a new directory ``assets/website/`` and move the following
files into the newly created directory, keeping the directory structure:

* ``assets/app.js``
* ``assets/styles/app.css``

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

Because of the above changes, you also have to adjust the
``config/packages/webpack_encore.yaml`` configuration file:

.. code:: diff

    webpack_encore:
        # The path where Encore is building the assets - i.e. Encore.setOutputPath()
   -    output_path: '%kernel.project_dir%/public/build'
   +    output_path: '%kernel.project_dir%/public/build/website'
        # If multiple builds are defined (as shown below), you can disable the default build:
        # output_path: false

.. code:: diff

    framework:
        assets:
   -        json_manifest_path: '%kernel.project_dir%/public/build/manifest.json'
   +        json_manifest_path: '%kernel.project_dir%/public/build/website/manifest.json'


Luckily, Webpack Encore provides a simple way to link to your
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

.. note::

   If the website assets were accidentally build before changing the `public/build` to `public/website/build`
   it could happen that all admin assets were removed. Use `git checkout public/build/admin` or the `bin/console sulu:admin:update-build` command to restore the admin build files: :doc:`build-admin-frontend`

Optional: Install Web-JS
------------------------

Follow the ``web-js`` install documentation located at the `web-js repository`_.

Customization
-------------

For further customization of your frontend setup, follow the `Webpack
Encore Documentation`_.

.. _WebpackEncoreBundle: https://github.com/symfony/webpack-encore-bundle
.. _Webpack Encore Documentation: https://symfony.com/doc/current/frontend.html#webpack-encore
.. _web-js repository: https://github.com/sulu/web-js
