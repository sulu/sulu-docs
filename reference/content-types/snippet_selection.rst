Snippet Selection
=================

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
    * - types
      - string
      - The type of snippet to assign.
    * - default
      - string - false
      - If this parameter is true or a specified area and enabled in config the
        content-type will load the snippet which can be specified by the
        content-manager in the webspace settings.
    * - loadExcerpt
      - boolean
      - If this parameter is set to true, then the taxonomies information for
        this snippet is loaded in a "taxonomies" property
    * - item_disabled_condition
      - string
      - Allows to set a jexl expression that evaluates if an item should be displayed as disabled.
        Disabled items cannot be selected.
    * - allow_deselect_for_disabled_items
      - bool
      - Defines if the user should be able to deselect an item that is disabled. Default value is true.
    * - request_parameters
      - collection
      - Collection of parameters that are appended to the requests sent by the selection.
    * - resource_store_properties_to_request
      - collection
      - Collection of property names.
        The value of the respective properties are appended to the requests sent by the selection.

.. note::

    The fallback mechanism has to be enabled in the config:
    `sulu_snippet.types.snippet.default_enabled`. (default activated since 1.4)

Example
-------

.. code-block:: xml

    <property name="snippets" type="snippet_selection">
        <meta>
            <title lang="en">Snippets</title>
        </meta>

        <params>
            <param name="types" value="sidebar"/>
            <param name="default" value="sidebar_overview"/>
            <param name="loadExcerpt" value="true"/>
        </params>
    </property>
