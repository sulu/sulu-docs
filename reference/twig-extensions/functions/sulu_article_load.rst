``sulu_article_load``
=====================

Returns article Structure with provided properties for the given UUID

.. code-block:: jinja

    {% set page = sulu_article_load('1234-1234-1234-1234', {
        'title': 'title',
        'article': 'article',
        'excerpt.title': 'excerpt.title',
        'url': 'url',
        'locale': 'object.locale',
        'webspaceKey': 'object.resource.webspaceKey'
    }) %}

**Arguments**:

- **uuid**: *string* - UUID of structure
- **properties**: *array* - Array of properties of the structure that should be loaded
- **locale**: *?string* - Locale in which the structure should be loaded (**optional**)

**Returns**:

.. include:: _structure.inc

.. note::

    Calling the ``sulu_article_load`` twig extension without the ``properties`` argument
    is not possible.
