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
    * - default
      - boolean - false
      - If this parameter is true and enabled in config the content-type will
        load the default snippets which can be specified by the content-manager
        in the webspace settings.

.. note::

    The fallback mechanism has to be enabled in the config:
    `sulu_snippet.types.snippet.default_enabled`.

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
