``sulu_snippet_load_by_area``
=============================

Returns the selected snippet from the webspace settings for the given :doc:`snippet area <../../../cookbook/default-snippets>`.

.. code-block:: jinja

    {% set snippets = sulu_snippet_load_by_area('sidebar_overview') %}
    {{ snippets.content.title }}

**Arguments**:

- **area**: *string* - The area to search for snippet.
- **webspaceKey**: *string* - optional: The webspace to get area snippet settings.
- **locale**: *string* - optional: The locale to load snippet.

**Returns**:

.. include:: _snippet_structure.inc
