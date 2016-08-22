Configure image formats
=======================

If you are using images, you probably care about the available image
formats. Sulu also supports you with that issue. You can define different image
formats, which Sulu will create for every uploaded image. This generation
is based on your configuration. You can define the formats in the file
`Resources/themes/<theme>/config/image-formats.xml` or
`%kernel.root_dir%/config/image-formats.xml` (if theme are not enabled).
Take a look at the following file for an example:

.. code-block:: xml

    <?xml version="1.0" encoding="UTF-8"?>
    <formats xmlns="http://schemas.sulu.io/media/formats"
             xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
             xsi:schemaLocation="http://schemas.sulu.io/media/formats http://schemas.sulu.io/media/formats-1.1.xsd">

        <format key="1200x800">
            <meta>
                <title lang="en">My header image format</title>
                <title lang="de">Mein Header-Bildformat</title>
            </meta>
            <scale x="1200" y="800"/>
        </format>
    </formats>

With the format tag you are creating a new image-format. The content in the key-attribute
has to be unique. It allows Sulu to identify the format. Within the meta tag you have the possibility
to define a title for the format in the different system languages.

The most notable part is the `scale` tag. It lets you define the dimensions uploaded images
have in your format. In our example the new image will be 1200 pixels in width and 800 pixels in height.
Because the default mode when scaling images is "outbound", the image will for sure have the size 1200x800.
The image will be scaled and possibly cropped, if it does not has the exact same ratio as the defined format.

The second mode available is "inset". As with the "outbound" mode, the image gets scaled. The difference
to "outbound" is, that the image gets scaled right to the point where it fits into the dimensions defined by
the image-format. This means that with mode "inset", images don't get cropped. To achieve this, we do
the following:

.. code-block:: xml

        <format key="1200x800">
            <meta>
                <title lang="en">My header image format</title>
                <title lang="de">Mein Header-Bildformat</title>
            </meta>
            <scale x="1200" y="800" mode="inset"/>
        </format>

A list of all available parameters for the `scale` tag is given below.

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - x
      - Positive integer
      - Defines the width of the image format
    * - y
      - Positive integer
      - Defines the height of the image format
    * - mode
      - String ("inset" or "outbound")
      - In `inset` mode images are just scaled down till they fit in a rectangle
        defined by `x` and `y`. In `outbound` an image is scaled down till it just includes
        a rectangle defined `x` and `y`. After that the parts outside the rectangle get cropped.
    * - forceRatio
      - Boolean
      - This parameter only takes effect, when using the `outbound` mode and is `true` by default.
        The `outbound` mode returns the original image, if it is smaller than the rectangle defined
        by your `x` and `y` parameters. However if this option is `true`, the image gets cropped, so
        it has the same ratio as your format.
    * - retina
      - Boolean
      - If set to true your format will actually be twice the size you defined.


Global image compression
------------------------

Images will not get compressed by default, if you upload them. You can set the
compression for images globally in the sulu.yml or for each image
format like in the next example.

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

Compression per format
----------------------

Quality, compression level as well as other options for `Imagine` can be defined
per image format. When there is also a global configuration the values of the image format
overrule the global ones. Let's define a compression for our image format.

.. code-block:: xml

        <format key="1200x800">
            <meta>
                <title lang="en">My header image format</title>
                <title lang="de">Mein Header-Bildformat</title>
            </meta>
            <scale x="1200" y="800"/>
            <options>
                <option name="jpeg_quality">70</option>
                <option name="png_compression_level">5</option>
            </options>
        </format>

Custom image-format location
----------------------------

Often it's needed to store image-formats in files other than `%kernel.root_dir%/config/image-formats.xml`
or the `image-formats.xml` file of a theme. With the configuration `sulu_media.image_format_files` this is possible
Just include the following lines in a config file (or prepend to the configuration with an extension).

.. code-block:: yaml

    sulu_media:
        image_format_files: ['path/to/image-formats1.xml', 'path/to/image-formats2.xml']
