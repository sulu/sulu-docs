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
already described in :doc:`setup-a-webspace`. This means that it is also very
easy to switch between different themes.


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
check the :doc:`adding-a-template`.


Enable the theme
----------------

For resolving the templates we are using the `LiipThemeBundle`_, which requires
you to register your themes. You can do that in your application configuration
located at `app/config/config.yml`. Add the name of your theme folder to the
following list:

.. code-block:: yaml

    liip_theme:
        themes: ["default", "your-new-shiny-theme"]


Configure image formats
-----------------------

If you are using images, you probably also care about the available image
formats. Sulu also supports you with that issue. You can define different image
formats, which Sulu will then create for every uploaded image. This generation
is based on your configuration. You can define the formats in the file 
`Resources/themes/<theme>/config/image-formats.xml`. Take a look at the
following file for an example:

.. code-block:: xml

    <?xml version="1.0" encoding="UTF-8"?>
    <formats xmlns="http://schemas.sulu.io/media/formats"
             xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
             xsi:schemaLocation="http://schemas.sulu.io/media/formats http://schemas.sulu.io/media/formats-1.0.xsd">
        <format>
            <name>640x480</name>
            <commands>
                <command>
                    <action>resize</action>
                    <parameters>
                        <parameter name="x">640</parameter>
                        <parameter name="y">480</parameter>
                    </parameters>
                </command>
            </commands>
            <!-- optional compression for this format -->
            <options>
                <option name="jpeg_quality">80</option>
                <option name="png_compression_level">6</option>
            </options>
        </format>
    </formats>

With the format tag you are creating a new image format. You have to name this
format, and create a list of commands to execute on it. The example will resize
the uploaded image to the size defined with the two parameters.

The next table shows the standard commands available with its parameters.

+---------+------------------------------------+
| Command | Parameters                         |
+=========+====================================+
| Resize  | x: the new width                   |
|         |                                    |
|         | y: the new height                  |
+---------+------------------------------------+
| Scale   | x: the new width                   |
|         |                                    |
|         | y: the new height                  |
|         |                                    |
|         | forceRatio: true/false             |
|         |                                    |
|         | mode: 'inset' or 'outbound'        |
+---------+------------------------------------+
| Crop    | x: x-coordinate of the startpoint  |
|         |                                    |
|         | y: y-coordinate of the startpoint  |
|         |                                    |
|         | w: the with of the new image       |
|         |                                    |
|         | h: the height of the new image     |
+---------+------------------------------------+

Global Image Compression
------------------------
Images will not get compressed by default, if you upload them. You can set the
compression for images globally in the sulu.yml or seperat for each image
format like in the example above.

To set the compression for all images you have to add following lines to your
``config.yml``:

.. code-block:: yaml

    sulu_media:
        format_manager:
            default_imagine_options:
                jpeg_quality: 80
                png_compression_level: 6

With the theme we got the container for our Twig-templates. That's what we'll
do next: Writing awesome Twig files.

.. _LiipThemeBundle: https://github.com/liip/LiipThemeBundle

