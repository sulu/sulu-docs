``sulu_article_load``
=====================

Returns article Structure with provided properties for the given UUID

.. code-block:: jinja

    {% set article = sulu_article_load('1234-1234-1234-1234', {
        'title': 'title',
        'article': 'article',
        'excerpt.title': 'excerpt.title',
        'url': 'url',
        'locale': 'object.locale',
        'mainWebspace': 'object.mainWebspace',
        'additionalWebspaces': 'object.additionalWebspaces'
    }) %}

**Arguments**:

- **uuid**: *string* - UUID of structure
- **properties**: *array* - Array of properties of the structure that should be loaded
- **locale**: *?string* - Locale in which the structure should be loaded (**optional**)

**Returns**: *array|null* - ``null`` if the article does not exist or is not published in the locale

- `array`
    - **resource**: Resource of article (e.g. uuid)
    - **content**: Content of article
    - **view**: View of article
    - **extension**: Extensions of article
    - **availableLocales**: Array of the available locales
    - **template**: Template name of article
    - **mainWebspace**: Key of the main webspace
    - **author**: Contact ID of the author (see :doc:`sulu_resolve_contact`)
    - **authored**: Authored date of article
    - **lastModified**: LastModified date of article
    - **shadowBaseLocale**: Shadow locale of article

.. note::

    Calling the ``sulu_article_load`` twig extension without the ``properties`` argument
    is not possible.
