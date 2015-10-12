Snippet
=======

Description
-----------

Shows a list with the possibility to assign an arbitrary amount of snippets.
Snippets are small blocks managed in the global section, which can be reused on
as many pages as necessary. The assigned snippets will be saved as an array of
references.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - snippetType
      - string
      - The type of snippet to assign.

Example
-------

.. code-block:: xml

    <property name="snippets" type="snippet">
        <meta>
            <title lang="en">Snippets</title>
        </meta>
        
        <params>
            <param name="snippetType" value="animal"/>
        </params>
    </property>
