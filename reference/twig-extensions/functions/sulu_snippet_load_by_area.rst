``sulu_snippet_load_by_area``
=============================

Returns the content of the default snippet for the given :doc:`snippet area <../../../cookbook/default-snippets>`.

.. code-block:: jinja

    {% set snippets = sulu_snippet_load_by_area('sidebar_overview') %}
    {{ snippets.content.title }}

**Arguments**:

- **area**: *string* - The area to search for snippet.
- **properties**: *array<string, string>* - optional: List of property names to resolve. By default all properties are resolved.
- **webspaceKey**: *string* - optional: The webspace to get area snippet settings.
- **locale**: *string* - optional: The locale to load snippet.

**Returns**:

.. include:: _snippet_structure.inc
