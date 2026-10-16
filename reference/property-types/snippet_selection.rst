Snippet Selection
=================

Description
-----------

Allows to select an arbitrary number of snippets. Snippets are reusable pieces of content that can be included on
multiple pages. The assigned snippets will be saved as an array of references.

Currently this property type does not support multiple areas and types.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - templateKeys
      - string
      - Comma separated list of template keys. If set, only snippets using one of these templates
        can be selected.
    * - groups
      - string
      - Comma separated list of group identifiers, available since Sulu 3.1. If set, only snippets
        belonging to one of these groups can be selected. See :ref:`templates-template-groups`.
    * - default
      - string
      - If set, the default snippet of the given area will be used as fallback value if no snippet is selected.
    * - properties
      - collection
      - Defines with which key which property of the linked snippet should be
        populated to the HTML template.
    * - item_disabled_condition
      - string
      - Allows to set a `jexl`_ expression that evaluates if an item should be displayed as disabled.
        Disabled items cannot be selected.
    * - allow_deselect_for_disabled_items
      - bool
      - Defines if the user should be able to deselect an item that is disabled. Default value is true.
    * - sortable
      - bool
      - Defines if the user should be able to sort the selected items. Default value is true.
    * - request_parameters
      - collection
      - Collection of parameters that are appended to the requests sent by the selection.
    * - resource_store_properties_to_request
      - collection
      - Collection of property names.
        The value of the respective properties are appended to the requests sent by the selection.
    * - min
      - string
      - The minimum number of selected snippets
    * - max
      - string
      - The maximum number of selected snippets

Example
-------

.. code-block:: xml

    <property name="snippets" type="snippet_selection">
        <meta>
            <title lang="en">Snippets</title>
        </meta>

        <params>
            <param name="templateKeys" value="sidebar"/>
            <param name="default" value="footer_social_media_links"/>
        </params>
    </property>

Twig
----

``content.snippets`` is a list with one entry per selected snippet. Each entry contains the fields of the snippet
template by their property name. If the ``properties`` parameter is set, it contains only the listed fields, under the
names given there. If no snippet is selected and the ``default`` parameter is set, the default snippet of that area in
the current webspace is used instead.

.. code-block:: twig

    {% for snippet in content.snippets %}
        {{ snippet.title }}
    {% endfor %}

When snippets are selected, ``view.snippets`` is a flat list in the same order as ``content.snippets``. Each entry
contains ``uuid`` and ``template`` (the template key of the snippet) and the view data of the snippet's fields, for
example the ``link`` of a link field. Without a selection, ``view.snippets`` is empty or holds only ``ids`` and the
parameters of the property. If the default snippet is used, ``view.snippets`` is no list: it holds ``ids`` with the uuid
of the default snippet and the parameters of the property, but no ``uuid``, ``template`` or field view data at the top
level.

.. code-block:: twig

    {% for snippet in content.snippets %}
        {{ snippet.title }} ({{ view.snippets[loop.index0].template }})
    {% endfor %}

.. _jexl: https://github.com/TomFrost/jexl
