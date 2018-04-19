Configure image formats
=======================

If you are using images, you probably also care about the available image
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
        <format key="640x480">
            <meta>
                <title lang="de">640x480</title>
                <title lang="en">640x480</title>
            </meta>
            <name>640x480</name>
            <scale x="640" y="480"/>
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
