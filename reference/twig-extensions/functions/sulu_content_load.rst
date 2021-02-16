``sulu_content_load``
=====================

Returns a Structure for the given UUID

.. code-block:: jinja

    {% set page = sulu_content_load('1234-1234-1234-1234', {'title': 'title', 'excerpt.title': 'excerptTitle'}) %}

**Arguments**:

- **uuid**: *string* - UUID of structure
- **properties**: *array* - Array of properties of the structure that should be loaded

**Returns**:

.. include:: _page_structure.inc

.. note::

    Calling the ``sulu_content_load`` twig extension without the ``properties`` argument
    loads and resolves all properties of the target. This is an expensive operation that has
    a negative impact on the performance and therefore is deprecated.
