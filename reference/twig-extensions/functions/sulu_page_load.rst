``sulu_page_load``
==================

Returns page Structure with provided properties for the given UUID

.. code-block:: jinja

    {% set page = sulu_page_load('1234-1234-1234-1234', {
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

**Returns**: *array|null* - ``null`` if the page does not exist or is not published in the locale

- `array`
    - **resource**: Resource of page (e.g. uuid, webspaceKey,...)
    - **content**: Content of page
    - **view**: View of page
    - **extension**: Extensions of page
    - **availableLocales**: Array of the available locales
    - **template**: Template name of page
    - **author**: Contact ID of the author (see :doc:`sulu_resolve_contact`)
    - **authored**: Authored date of page
    - **lastModified**: LastModified date of page
    - **shadowBaseLocale**: Shadow locale of page

.. note::

    Calling the ``sulu_page_load`` twig extension without the ``properties`` argument
    is not possible.
