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
    * - scripts
      - boolean
      - Allow script tags in source code
    * - iframes
      - boolean
      - Allow iframe tags in source code
    * - height
      - integer
      - Set the text editor height
    * - maxHeight
      - integer
      - Set the max height for autogrow '0' is  infinitely
    * - enterMode
      - string P/BR/DIV
      - Set what happens on enter allowed P or BR
    * - pasteFromWord
      - boolean
      - Adds a button to paste content from word to the text editor. If you add
        text via this button, some characters which could cause troubles are
        removed.

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
            <param name="scripts" value="true"/>
            <param name="iframes" value="true"/>
            <param name="height" value="300"/>
            <param name="maxHeight" value="500"/>
            <param name="enterMode" value="P"/>
            <param name="pasteFromWord" value="true"/>
        </params>
    </property>
