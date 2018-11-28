How to Override Sulu's default Directory Structure
==================================================

As sulu is symfony based you can have a look at the `symfony documentation`_ about override the default structure.
Keep in mind that the cache folder of sulu need to be different for Kernel::getContext() website and admin.

Override admin js/css build basePath
------------------------------------

If you not only want to overwrite the `public-dir`_ but also the path where the js/css for the admin is build,
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

Also you need to tell framework bundle where it will find the new ``manifest.json`` after you did
generate it with ``npm install`` and ``npm run build`` into your new directory.

.. code-block:: yaml

    # config/packages/framework.yaml
    framework:
        assets:
            packages:
                sulu_admin:
                    json_manifest_path: "%kernel.project_dir%/public/your/new/path/manifest.json"

.. _symfony documentation: https://symfony.com/doc/current/configuration/override_dir_structure.html
.. _public_dir: https://symfony.com/doc/current/configuration/override_dir_structure.html#override-the-public-directory
