How to Override Sulu's default Directory Structure
==================================================

As Sulu is Symfony based you can read about overriding the default structure in the `Symfony documentation`_.
Keep in mind that the cache folder of Sulu needs to be different for Kernel::getContext() website and admin.

Override admin js/css build basePath
------------------------------------

If you not only want to override the `public-dir`_ but also the path where the js/css for the admin is built,
you need to change the following in your webpack configuration:

.. code-block:: js

    const webpackConfig = require('./vendor/sulu/sulu/webpack.config.js');

    module.exports = (env, argv) => {
        if (!env) {
            env = {};
        }

        env.base_path = 'your/new/path';

        return webpackConfig(env, argv);
    };

Also you need to tell the framework bundle where it will find the new ``manifest.json`` after you
generated it with ``npm install`` and ``npm run build`` into your new directory.

.. code-block:: yaml

    # config/packages/framework.yaml
    framework:
        assets:
            packages:
                sulu_admin:
                    json_manifest_path: "%kernel.project_dir%/public/your/new/path/manifest.json"

.. _Symfony documentation: https://symfony.com/doc/current/configuration/override_dir_structure.html
.. _public-dir: https://symfony.com/doc/current/configuration/override_dir_structure.html#override-the-public-directory


Overwrite templates configuration files path
--------------------------------------------

To use another directory then the default ``config/templates/pages``. You need to create a ``sulu_core.yaml`` file in ``config/packages`` and add the following parameters.
(Subdirectories are not included by design, this allows the use of subdirectories for something else like ``<xi:include .../>`` see :doc:`../book/templates`.)

.. code-block:: yaml

    # config/packages/sulu_core.yaml
    sulu_core:
        content:
            structure:
                paths:
                    page_projectA:
                        path: '%kernel.project_dir%/config/templates/pages/projectA'
                        type: page
                    page_projectB:
                        path: '%kernel.project_dir%/config/templates/pages/projectB'
                        type: page

Or use the environment variable ``SITE`` for the active Webspace.

.. code-block:: yaml

    # config/packages/sulu_core.yaml
    sulu_core:
        content:
            structure:
                paths:
                    page_site:
                        path: '%kernel.project_dir%/config/sites/%env(SITE)%/templates'
                        type: page



Overwrite Webspaces config file path
------------------------------------

.. code-block:: yaml

    # config/packages/sulu_core.yaml
    sulu_core:
        webspace:
            config_dir: '%kernel.project_dir%/config/sites/%env(SITE)%'

