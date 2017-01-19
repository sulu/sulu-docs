Adding a theme
==============

In the theme we'll define how things look to the user. You know HTML, CSS, JS
and such stuff.


What is a theme
---------------

A theme defines the way the content from Sulu is presented on the website. In
general it's not more than a simple folder containing all the required twig
templates, images, scripts, fonts and all the other assets you want to use in
this specific theme.

You can have multiple themes in one Sulu installation. Every webspace can
decide which theme to use, by a simple key in the webspace configuration file
already described in :doc:`webspaces`. This means that it is also very
easy to switch between different themes.

This feature is not shipped with a minimal sulu-installation. But can be easily
integrated.

Installation
------------

First add the dependency to the `SuluThemeBundle`_ in your `composer.json` file.

.. code-block:: xml

    composer require sulu/theme-bundle

To enable it add the following lines into the `app/AbstractKernel.php` and
`app/config/config.yml`.

.. code-block:: xml

    abstract class AbstractKernel extends SuluKernel
    {
        /**
         * {@inheritdoc}
         */
        public function registerBundles()
        {
            $bundles = [
                ...

                new Sulu\Bundle\ThemeBundle\SuluThemeBundle(),
                new Liip\ThemeBundle\LiipThemeBundle(),

                ...
            ];

            ...

            return $bundles;
        }
    }

.. code-block:: yaml

    # LIIP Theme Configuration
    liip_theme:
        themes: ["default"]
        active_theme: "default"
        load_controllers: false
        assetic_integration: true

This will configure a default theme which can be enabled in the
`app/Resources/webspaces/<webspace>.xml` file by adding:

.. code-block:: xml

    <theme>default</theme>

Create a theme
--------------

Creating a theme is as easy as creating a new folder in the `Resources/themes/`
folder of your bundle with the name of the new theme. Afterwards you have to
fill this folder with all the used templates in the webspace. These templates
go into another subfolder in your theme, which you have to reference later. We
recommend to name this folder `templates`. It is also recommended to create
a folder `views` for more general templates, like the master template, an
error page, etc., and a folder `blocks` for reusable templates, like the seo
information.

For more concrete information about the structure of these templates you should
check the :doc:`templates`.


Enable the theme
----------------

For resolving the templates we are using the `LiipThemeBundle`_, which requires
you to register your themes. You can do that in your application configuration
located at `app/config/config.yml`. Add the name of your theme folder to the
following list:

.. code-block:: yaml

    liip_theme:
        themes: ["default", "your-new-shiny-theme"]

.. _LiipThemeBundle: https://github.com/liip/LiipThemeBundle
.. _`Theme cascading order`: https://github.com/liip/LiipThemeBundle#theme-cascading-order
.. _SuluThemeBundle: https://github.com/sulu/SuluThemeBundle
