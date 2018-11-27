How to Override Sulu's default Directory Structure
==================================================

As sulu is symfony based you can have a look at the `symfony documentation`_ about override the default structure.
Keep in mind that the cache folder of sulu need to be different for Kernel::getContext() website and admin.

Override admin js/css build folder
----------------------------------

To override the js build folder you need to modify the webpack configuration using the new path:

.. code-block:: js

    const path = require('path');
    const webpackConfig = require('./vendor/sulu/sulu/webpack.config.js');

    module.exports = (env, argv) => {
        let config = webpackConfig(env, argv);

        config.output.path = path.resolve('public/your/new/path');

        return config;
    };

Also you need to tell framework bundle where it will find the new ``manifest.json`` generated after you did
``npm install`` and ``npm run build``.

.. code-block:: yaml

    # config/packages/framework.yaml
    framework:
        assets:
            packages:
                sulu_admin:
                    json_manifest_path: "%kernel.project_dir%/public/your/new/path/manifest.json"

.. _symfony documentation: https://symfony.com/doc/current/configuration/override_dir_structure.html
