``sulu_snippet_load_by_type``
=============================

Returns all snippets for the given snippet type.

.. code-block:: jinja

    {% set snippets = sulu_snippet_load_by_type('foo') %}
    {% for snippet in snippets %}
        {{ snippet.title }}
    {% endfor %}

**Arguments**:

- **type**: *string* - The type of snippets to load.
- **locale**: *string* - optional: The locale to load snippets.

**Returns**:

An array of snippets of the given type with title and custom defined fields.
