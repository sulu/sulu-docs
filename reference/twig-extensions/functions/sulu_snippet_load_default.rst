``sulu_snippet_load_default``
=============================

Returns content array of selected default snippet for given snippet type.

.. code-block:: jinja

    {% set snippets = sulu_snippet_load_default('default') %}
    {{ snippets[0].title }}

.. note::

    The system currently only support one default per type.

**Arguments**:

- **snippetType**: *string* - The type to search for default snippets.
- **webspaceKey**: *string* - optional: The webspace to get default snippet
                                        settings.
- **locale**: *string* - optional: The locale to load snippet.

**Returns**:

An array of:

.. include:: _snippet_structure.inc
