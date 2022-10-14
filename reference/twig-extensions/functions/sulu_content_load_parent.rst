``sulu_content_load_parent``
============================

Return the parent of the Structure with the given UUID

.. code-block:: jinja

    {% set page = sulu_content_load_parent('1234-1234-1234-1234', {
        'title': 'title',
        'excerptTitle': 'excerpt.title',
        'url': 'url',
    }) %}

**Arguments**:

- **uuid**: *string* - UUID of child structure
- **properties**: *array* - Array of properties of the structure that should be loaded

**Returns**:

.. include:: _page_structure.inc

.. note::

    Calling the ``sulu_content_load_parent`` twig extension without the ``properties`` argument
    loads and resolves all properties of the target. This is an expensive operation that has
    a negative impact on the performance and therefore is deprecated.
