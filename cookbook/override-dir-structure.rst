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


Load Snippets from a Subfolder
------------------------------
By the means of configuration in `config/packages/sulu_admin.yaml` according to the following scheme
it is also possible to load snippet templates from custom folders.

.. code-block:: yaml

    sulu_core:
        content:
            structure:
                paths:
                    event_snippets:
                        path: "%kernel.project_dir%/config/template/events/snippets/"
                        type: "snippet"

In this example, a new Events folder has been specified. It is important that the key for the configuration remains unique for each config.

.. _Symfony documentation: https://symfony.com/doc/current/configuration/override_dir_structure.html
.. _public-dir: https://symfony.com/doc/current/configuration/override_dir_structure.html#override-the-public-directory
