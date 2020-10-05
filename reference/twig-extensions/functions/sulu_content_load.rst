``sulu_content_load``
=====================

Returns a Structure for the given UUID

.. code-block:: jinja

    {% set page = sulu_content_load('1234-1234-1234-1234-1234') %}

**Arguments**:

- **uuid**: *string* - UUID of structure

**Returns**:

.. include:: _page_structure.inc


.. note::

    The ``sulu_content_load`` twig extension loads and resolves the whole page content.
    This is an expensive operation and can have a negative impact on the performance of 
    the page. If possible, the extension should be avoided.
