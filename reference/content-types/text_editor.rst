Text editor
===========

Description
-----------

Shows a rich text editor, capable of formatting text as well. The output of the
editor will be stored as HTML in a string field.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - tables
      - boolean
      - Adds tools for creating tables to the text editor
    * - links
      - boolean
      - Adds buttons for creating links to the text editor
    * - paste_from_word
      - boolean
      - Adds a button to paste content from word to the text editor. If you add
        text via this button, some characters which could cause troubles are
        removed.
    * - height
      - integer
      - Sets the initialize height of the texteditor.
    * - max_height
      - integer
      - Sets the maximum height to which the texteditor can grow.
      
Texteditor supports also all `ckeditor config`_ parameters in snakecase.

.. _ckeditor config: http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg

Example
-------

.. code-block:: xml

    <property name="article" type="text_editor">
        <meta>
            <title lang="en">Article</title>
        </meta>

        <params>
            <param name="tables" value="true"/>
            <param name="links" value="true"/>
            <param name="pasteFromWord" value="true"/>
        </params>
    </property>
