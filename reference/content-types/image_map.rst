ImageMap
========

Description
-----------

The image map content type allows to assign an arbitrary amount of hotspots to
an image. These hotspots can be either circles, rectangles or points. It's
possible to define multiple types with different content types included. Every
hotspot needs to be one of the defined types.

A quite common use case is to create a type containing a text editor. Then it's
possible to describe different parts of an image using text. In the front-end
this could be implemented using hover areas. If the user hovers over a hotspot,
the corresponding text could be shown in a popover.

Parameters
----------

No parameters available

Example
-------

.. code-block:: xml

    <property type="image_map" name="special_image" default-type="text">
        <meta>
            <title lang="de">Spezial-Bild</title>
            <title lang="en">Special image</title>
        </meta>
        <types>
            <type name="text">
                <meta>
                    <title lang="de">Text</title>
                    <title lang="en">Text</title>
                </meta>
                <properties>
                    <property name="description" type="text_editor">
                        <meta>
                            <title lang="de">Beschreibung</title>
                            <title lang="en">Description</title>
                        </meta>
                    </property>
                </properties>
            </type>
        </types>
    </property>
