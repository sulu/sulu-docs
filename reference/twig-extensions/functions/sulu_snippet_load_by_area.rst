``sulu_snippet_load_by_area``
=============================

Returns the content of the default snippet for the given :doc:`snippet area <../../../cookbook/default-snippets>`.

.. code-block:: jinja

    {% set snippets = sulu_snippet_load_by_area('sidebar_overview') %}
    {{ snippets.content.title }}

**Arguments**:

- **areaKey**: *string* - The key of the snippet area to load the snippet of.
- **properties**: *array<string, string>* - optional: List of property names to resolve. By default all properties are resolved.
- **webspaceKey**: *string* - optional: The webspace to get area snippet settings. Default to the webspace of the
  current request.
- **locale**: *string* - optional: The locale to load snippet. Default to the locale of the current request.

**Returns**: *array|null* - ``null`` if no snippet is assigned to the area or it is not published in the locale

.. include:: _snippet_structure.inc
