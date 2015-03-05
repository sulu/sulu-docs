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
            <param name="pasteFromWord" value="true"/>
        </params>
    </property>
