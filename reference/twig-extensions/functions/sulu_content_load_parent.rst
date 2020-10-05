``sulu_content_load_parent``
============================

Return the parent of the Structure with the given UUID

.. code-block:: jinja

    {% set page = sulu_content_load_parent('1234-1234-1234-1234-1234') %}

**Arguments**:

- **uuid**: *string* - UUID of structure parent

**Returns**:

.. include:: _page_structure.inc

.. note::

    The ``sulu_content_load_parent`` twig extension loads and resolves the whole page content.
    This is an expensive operation and can have a negative impact on the performance of 
    the page. If possible, the extension should be avoided.
