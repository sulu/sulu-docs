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

The directories of the template XML files are configured per template type in ``sulu_admin.templates``. The types are
``page``, ``snippet``, ``article`` and ``block``. By default each type has one directory registered under the name
``app``: ``config/templates/pages``, ``config/templates/snippets``, ``config/templates/articles`` and
``config/templates/blocks``.

To use further directories, add them to ``directories`` under a new name. To replace the default directory,
use the name ``app``. A directory that does not exist is skipped. Do not add a subdirectory of a configured directory
(like ``config/templates/pages/projectA``), because the files of subdirectories are loaded already.

.. code-block:: yaml

    # config/packages/sulu_admin.yaml
    sulu_admin:
        templates:
            page:
                directories:
                    page_projectA: '%kernel.project_dir%/config/templates/projectA/pages'
                    page_projectB: '%kernel.project_dir%/config/templates/projectB/pages'

Or use the environment variable ``SITE`` for the active Webspace and replace the default directory.

.. code-block:: yaml

    # config/packages/sulu_admin.yaml
    sulu_admin:
        templates:
            page:
                directories:
                    app: '%kernel.project_dir%/config/sites/%env(SITE)%/templates'

Every ``*.xml`` file in a directory and in its subdirectories is loaded, and the properties of files with the same
``<key>`` are combined. Define the title, view and controller of a template in one file only, because the load order is
not guaranteed. Keep ``<xi:include .../>`` fragments outside of these directories, see :doc:`../book/templates`.

``default_type`` sets the template key that the administration interface preselects for new content. Without it, the
first loaded template is preselected, in no guaranteed order. For pages, the default template of the webspace is
preselected.

The key is also used when content is saved without a template. In Sulu 3.0 such a save fails if ``default_type`` is not
set:

.. code-block:: yaml

    # config/packages/sulu_admin.yaml
    sulu_admin:
        templates:
            snippet:
                default_type: 'my_snippet_key'

Overwrite Webspaces config file path
------------------------------------

.. code-block:: yaml

    # config/packages/sulu_core.yaml
    sulu_core:
        webspace:
            config_dir: '%kernel.project_dir%/config/sites/%env(SITE)%'

