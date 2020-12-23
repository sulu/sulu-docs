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

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - ``add_button_text``
      -
      - Allows to adjust the text of the add button that is displayed in the administration interface.
    * - ``collapsable``
      - boolean
      - If set to ``true`` blocks can be collapsed, otherwise they are always expanded. Defaults to ``true``.
    * - ``movable``
      - boolean
      - If set to ``true`` the position of blocks can be changed by using drag&drop, otherwise they have a fixed position. Default to ``true``.
    * - ``settings_form_key``
      - string
      - Key of the form that should be opened in an overlay when the settings icon is clicked. Will be set to ``page_block_settings`` per default for pages.

Example
-------

Please note that the configuration of the block content type differs from the
other content types.

Instead of a ``property``-tag a ``block``-tag is used. The
``default-type``-attribute is mandatory and describes which of the types are
used by default.

The other essential attribute is the ``types``-tag, which contains multiple
``type``-tags. A type defines some titles and its containing ``properties``,
whereby all the available :doc:`index` can be used. These types are offered 
to the content manager via dropdown.

If collapsed the system will show the content of three properties in the block
by default, in order to give the content manager an idea which block they are
seeing. The ``sulu.block_preview`` tag can be used to manually choose which
properties should be shown as a preview in collapsed blocks. These tags
additionally take a ``priority`` attribute, which can alter the order of the
property previews.

The example only shows a single type, combining a media selection with a text
editor as described in the description.

.. code-block:: xml

    <block name="blocks" default-type="editor_image" minOccurs="0">
        <meta>
            <title lang="de">Inhalte</title>
            <title lang="en">Content</title>
        </meta>

        <params>
            <param name="add_button_text">
                <meta>
                    <title lang="en">Add track</title>
                    <title lang="de">Track hinzufügen</title>
                </meta>
            </param>
        </params>

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

                        <tag name="sulu.block_preview" priority="512"/>
                    </property>

                    <property name="article" type="text_editor" colspan="9">
                        <meta>
                            <title lang="de">Artikel</title>
                            <title lang="en">Article</title>
                        </meta>

                        <tag name="sulu.block_preview" priority="1024"/>
                    </property>
                </properties>
            </type>
        </types>
    </block>

Twig
----

A reusable way for rendering blocks is having a separate template file per type:

.. code-block:: twig

    {% for block in content.blocks %}
        {% include 'includes/blocks/' ~ block.type ~ '.html.twig' with {
            content: block,
            view: view.blocks[loop.index0],
        } %}
    {% endfor %}

This way, its possible to access the ``properties`` of the block type via
the ``content`` and ``view`` variable in the rendered block template.

Extending default block settings for pages
------------------------------------------

If you want to add a custom field to the block settings for pages, you can extend
the ``page_block_settings`` form by creating a ``config/forms/page_block_settings``:

.. code-block:: xml

    <?xml version="1.0" ?>
    <form xmlns="http://schemas.sulu.io/template/template"
          xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
          xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/form-1.0.xsd"
    >
        <key>page_block_settings</key>

        <properties>
            <section name="custom">
                <properties>
                    <property name="theme" type="single_select">
                        <meta>
                            <title lang="en">Block Theme</title>
                            <title lang="de">Block Theme</title>
                        </meta>

                        <params>
                            <param name="default_value" value=""/>

                            <param name="values" type="collection">
                                <param name="">
                                    <meta>
                                        <title lang="en">Default</title>
                                        <title lang="de">Standard</title>
                                    </meta>
                                </param>

                                <param name="highlight">
                                    <meta>
                                        <title lang="en">Highlight</title>
                                        <title lang="de">Highlight</title>
                                    </meta>
                                </param>
                            </param>
                        </params>
                    </property>
                </properties>
            </section>
        </properties>
    </form>

The value of your field can be accessed in twig over the ``settings`` variable:

.. code-block:: twig

    {% for block in content.blocks %}
        <div class="blocks__item{% if block.settings.theme|default %} block__item--{{ block.settings.theme }}{% endif %}">
            {# ... #}
        </div>
    {% endfor %}
