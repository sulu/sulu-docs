Block
=====

Description
-----------

The block content type allows to group an arbitrary amount of other content
types. Each block can define multiple types with with different content types
included. These blocks can then be repeated and ordered by the content manager
in the Sulu-Admin.

A quite common use case is to combine a text editor with a media selection.
This way a text can be directly linked to an image via the assignment to the
same block. This approach has its biggest benefit over putting images into the
text editor when used in combination with responsive design. When using
multiple content types in a block the template developer has the freedom to
place the image where and in which format it makes sense. In contrast, adding
images to the text editor would make it quite hard to adapt the format and
placement in the twig template.

Parameters
----------

No parameters available

Example
-------

Please note that the configuration of the block content type differs from the
other content types.

Instead of a ``property``-tag a ``block``-tag is used. The
``default-type``-attribute is mandatory and describes which of the types are
use by default.

The other essential attribute is the ``types``-tag, which contains multiple
``type``-tags. A type defines some titles and its containing ``properties``,
whereby all the available :doc:`index` (except the block itself, since we do
not support nesting) can be used. These types are offered to the content
manager via dropdown.

The example only shows a single type, combining a media selection with a text
editor as described in the description.

.. code-block:: xml

    <block name="blocks" default-type="editor" minOccurs="0">
        <meta>
            <title lang="de">Inhalte</title>
            <title lang="en">Content</title>
        </meta>
        <types>
            <type name="editor_image">
                <meta>
                    <title lang="de">Editor mit Bild</title>
                    <title lang="en">Editor with image</title>
                </meta>
                <properties>
                    <property name="images" type="media_selection" colspan="3">
                        <meta>
                            <title lang="de">Bilder</title>
                            <title lang="en">Images</title>
                        </meta>
                        <params>
                            <param name="type" value="image"/>
                            <param name="displayOptions" type="collection">
                                <param name="leftTop" value="false"/>
                                <param name="top" value="true"/>
                                <param name="rightTop" value="false"/>
                                <param name="left" value="true"/>
                                <param name="middle" value="false"/>
                                <param name="right" value="true"/>
                                <param name="leftBottom" value="false"/>
                                <param name="bottom" value="true"/>
                                <param name="rightBottom" value="false"/>
                            </param>
                        </params>
                    </property>

                    <property name="article" type="text_editor" colspan="9">
                        <meta>
                            <title lang="de">Artikel</title>
                            <title lang="en">Article</title>
                        </meta>
                    </property>
                </properties>
            </type>
        </types>
    </block>
