``sulu_snippet_load``
=====================

Returns content array for given snippet uuid.

.. code-block:: jinja

    {% set snippet = sulu_snippet_load('1234-1234-1234-1234-1234') %}
    {{ snippet.content.title }}

**Arguments**:

- **uuid**: *string* - The uuid of requested content.
- **locale**: *string* - optional: Locale to load snippet.

**Returns**:

.. include:: _snippet_structure.inc
