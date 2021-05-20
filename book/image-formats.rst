Configure image formats
=======================

Sulu supports to configure image formats for images that are uploaded via the administration interface.
This allows to optimize the performance of your website by outputting images in the exact dimensions that
are needed. Furthermore, the content manager can select the cutout that should be used when outputting a
specific image format for an uploaded image.

To save space on your server, image formats for uploaded images are generated when they are requested the
first time.

Image formats can be defined in:

 - `config/image-formats.xml`

Or when you use the SuluThemeBundle you can define the formats in your theme folder:

 - `path/to/<theme>/config/image-formats.xml`

The following example shows you different ways to define image formats:

.. code-block:: xml

    <?xml version="1.0" encoding="UTF-8"?>
    <formats xmlns="http://schemas.sulu.io/media/formats"
             xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
             xsi:schemaLocation="http://schemas.sulu.io/media/formats http://schemas.sulu.io/media/formats-1.1.xsd">

        <!-- Fixed width and dynamic height -->
        <format key="300x">
            <scale x="300"/>
        </format>

        <!-- Dynamic width and fixed height -->
        <format key="x200">
            <scale y="200"/>
        </format>

        <!-- Fixed width and fixed height -->
        <format key="300x200">
            <scale x="300" y="200"/>
        </format>

        <!-- Max width and max height -->
        <format key="300x200-inset">
            <scale x="300" y="200" mode="inset"/>
        </format>
    </formats>

Defining meta title
-------------------

You should define a meta title for every image format, since these titles are
shown to the content manager in the administration interface when cropping the
images.

.. code-block:: xml

    <?xml version="1.0" encoding="UTF-8"?>
    <formats xmlns="http://schemas.sulu.io/media/formats"
             xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
             xsi:schemaLocation="http://schemas.sulu.io/media/formats http://schemas.sulu.io/media/formats-1.1.xsd">
        <format key="300x">
            <meta>
                <title lang="en">Author Avatar</title>
                <title lang="de">Autor Avatar</title>
            </meta>

            <scale x="300"/>
        </format>

        <format key="920x">
            <meta>
                <title lang="en">Header</title>
                <title lang="de">Header</title>
            </meta>

            <scale x="920"/>
        </format>
    </formats>

Image Compression
-----------------

Global image compression
^^^^^^^^^^^^^^^^^^^^^^^^

Images will not get compressed by default when you upload them. You can set the
compression for images globally e.g. in the `config/packages/sulu_media.yml`.
This file does not exist by default and must be created on your own.

.. code-block:: yaml

    sulu_media:
        format_manager:
            default_imagine_options:
                jpeg_quality: 80
                webp_quality: 80
                png_compression_level: 6

Its recommended to have `jpeg_quality` between 70-90 as this is the best compromise between quality and image size.

Specific image compression
^^^^^^^^^^^^^^^^^^^^^^^^^^

A image compression can also be set on a specific image format the following way:

.. code-block:: xml

    <?xml version="1.0" encoding="UTF-8"?>
    <formats xmlns="http://schemas.sulu.io/media/formats"
             xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
             xsi:schemaLocation="http://schemas.sulu.io/media/formats http://schemas.sulu.io/media/formats-1.1.xsd">
        <format key="300x">
            <scale x="300"/>

            <options>
                <option name="jpeg_quality">80</option>
                <option name="webp_quality">80</option>
                <option name="png_compression_level">6</option>
            </options>
        </format>
    </formats>

Transformations
---------------

There are several transformations available in sulu to add some effects to your images:

Blur
^^^^

Will blur the image by a given `sigma` parameter:

.. code-block:: xml

    <?xml version="1.0" encoding="UTF-8"?>
    <formats xmlns="http://schemas.sulu.io/media/formats"
             xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
             xsi:schemaLocation="http://schemas.sulu.io/media/formats http://schemas.sulu.io/media/formats-1.1.xsd">
        <format key="300x-blur">
            <scale x="300"/>

            <transformations>
                <transformation>
                    <effect>blur</effect>
                    <parameters>
                        <parameter name="sigma">6</parameter>
                    </parameters>
                </transformation>
            </transformations>
        </format>
    </formats>

Grayscale
^^^^^^^^^

Will convert the image into a black/white image:

.. code-block:: xml

    <?xml version="1.0" encoding="UTF-8"?>
    <formats xmlns="http://schemas.sulu.io/media/formats"
             xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
             xsi:schemaLocation="http://schemas.sulu.io/media/formats http://schemas.sulu.io/media/formats-1.1.xsd">
        <format key="300x-black">
            <scale x="300"/>

            <transformations>
                <!-- Black/white effect -->
                <transformation>
                    <effect>grayscale</effect>
                </transformation>
            </transformations>
        </format>
    </formats>

Gamma
^^^^^

Will add a gamma effect by a given `correction` parameter:

.. code-block:: xml

    <?xml version="1.0" encoding="UTF-8"?>
    <formats xmlns="http://schemas.sulu.io/media/formats"
             xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
             xsi:schemaLocation="http://schemas.sulu.io/media/formats http://schemas.sulu.io/media/formats-1.1.xsd">
        <format key="300x-gamma">
            <scale x="300"/>

            <transformations>
                <!-- Gamma effect -->
                <transformation>
                    <effect>gamma</effect>
                    <parameters>
                        <parameter name="correction">0.7</parameter>
                    </parameters>
                </transformation>
            </transformations>
        </format>
        </format>
    </formats>

Sharpen
^^^^^^^

Will add a sharpen effect:

.. code-block:: xml

    <?xml version="1.0" encoding="UTF-8"?>
    <formats xmlns="http://schemas.sulu.io/media/formats"
             xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
             xsi:schemaLocation="http://schemas.sulu.io/media/formats http://schemas.sulu.io/media/formats-1.1.xsd">
        <format key="300x-sharpen">
            <scale x="300"/>

            <transformations>
                <!-- Sharpen effect -->
                <transformation>
                    <effect>sharpen</effect>
                </transformation>
            </transformations>
        </format>
    </formats>

Paste
^^^^^

The paste transformation effect will add another image on top on the rendered image.
This can be used to add a border or a copyright to the image.

.. code-block:: xml

    <?xml version="1.0" encoding="UTF-8"?>
    <formats xmlns="http://schemas.sulu.io/media/formats"
             xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
             xsi:schemaLocation="http://schemas.sulu.io/media/formats http://schemas.sulu.io/media/formats-1.1.xsd">
        <format key="300x300-border">
            <scale x="300" y="300"/>

            <transformations>
                <!-- Paste effect -->
                <transformation>
                    <effect>paste</effect>
                    <parameters>
                        <parameter name="image">@AppBundle/Resources/public/border.png</parameter>
                    </parameters>
                </transformation>
            </transformations>
        </format>
    </formats>

The given image can be positioned by adding `x`, `y`, `w` `h` parameter:

.. code-block:: xml

    <?xml version="1.0" encoding="UTF-8"?>
    <formats xmlns="http://schemas.sulu.io/media/formats"
             xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
             xsi:schemaLocation="http://schemas.sulu.io/media/formats http://schemas.sulu.io/media/formats-1.1.xsd">
        <format key="300x300-border">
            <scale x="300" y="300"/>

            <transformations>
                <!-- Paste effect -->
                <transformation>
                    <effect>paste</effect>
                    <parameters>
                        <parameter name="image">@AppBundle/Resources/public/border.png</parameter>
                        <parameter name="x">0</parameter>
                        <parameter name="y">0</parameter>
                        <parameter name="w">300</parameter>
                        <parameter name="h">300</parameter>
                    </parameters>
                </transformation>
            </transformations>
        </format>
    </formats>

Combining Transformations
^^^^^^^^^^^^^^^^^^^^^^^^^

Transformation effect can also be combined the following way:

.. code-block:: xml

    <?xml version="1.0" encoding="UTF-8"?>
    <formats xmlns="http://schemas.sulu.io/media/formats"
             xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
             xsi:schemaLocation="http://schemas.sulu.io/media/formats http://schemas.sulu.io/media/formats-1.1.xsd">
        <format key="300x-blur-black">
            <scale x="300"/>

            <transformations>
                <transformation>
                    <effect>blur</effect>
                    <parameters>
                        <parameter name="sigma">6</parameter>
                    </parameters>
                </transformation>

                <transformation>
                    <effect>grayscale</effect>
                </transformation>
            </transformations>
        </format>
    </formats>

Editing exist image formats
---------------------------

If you modify existing image formats, you need to remove already generated images by executing the following
command:

.. code-block:: bash

    php bin/websiteconsole sulu:media:format:cache:clear

This will delete all generated image formats on the server. A new image will be generated on the first request
of the image format.

Remove obsolete images
----------------------

In a multi server setup, when an image is removed via the administration interface, existing image formats are only
removed on the current server.
Generated image formats of media that do not exist in the database can be removed by running the following command:

.. code-block:: bash

   php bin/websiteconsole sulu:media:format:cache:cleanup
