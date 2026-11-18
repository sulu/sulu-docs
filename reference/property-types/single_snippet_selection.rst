Single Snippet Selection
========================

Description
-----------

Allows to select a single snippet. Snippets are reusable pieces of content that can be included on multiple pages.

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
    * - request_parameters
      - collection
      - Collection of parameters that are appended to the requests sent by the selection.
    * - resource_store_properties_to_request
      - collection
      - Collection of property names.
        The value of the respective properties are appended to the requests sent by the selection.

Example
-------

.. code-block:: xml

    <property name="snippet" type="single_snippet_selection">
        <meta>
            <title lang="en">Snippet</title>
        </meta>

        <params>
            <param name="templateKeys" value="default"/>
            <param name="default" value="footer_social_media_links"/>
        </params>
    </property>

Twig
----

``content.snippet`` is an object with the fields of the snippet template by their property name. If the ``properties``
parameter is set, it contains only the listed fields, under the names given there. If no snippet is selected and the
``default`` parameter is set, the default snippet of that area in the current webspace is used instead. If there is no
default snippet either, ``content.snippet`` is ``null`` or contains no snippet fields, so check a field:

.. code-block:: twig

    {% if content.snippet.title is defined %}
        {{ content.snippet.title }}
    {% endif %}

When a snippet is selected, ``view.snippet`` is an object with ``id``, ``uuid`` and ``template`` (the template key of
the snippet), the view data of the snippet's fields and the parameters of the property. Without a selection and without
``default``, it only contains ``id`` (``null``) and the parameters. If the default snippet is used, it holds ``id`` (the
uuid of the default snippet) and the parameters, but no ``uuid``, ``template`` or field view data at the top level. If
``default`` is set but the area has no snippet, it holds only the parameters.

.. code-block:: twig

    {{ view.snippet.template }}

.. _jexl: https://github.com/TomFrost/jexl
