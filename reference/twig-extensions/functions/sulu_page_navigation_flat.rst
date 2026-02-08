``sulu_page_navigation_flat``
=============================

Returns the navigation for a given page uuid as a list of pages:

**Example**:

.. code-block:: twig

    {% for item in sulu_page_navigation_flat(resource.uuid, 'main') %}
        <a href="{{ sulu_content_path(item.url) }}">{{ item.title }}</a>
    {% endfor %}

**Complex Example**:

.. code-block:: twig

    {% for item in sulu_page_navigation_flat(resource.uuid, 'main', 1, 2, {
        'title': 'title',
        'url': 'url',
        'excerptTitle': 'excerpt.title',
    }) %}
        <a href="{{ sulu_content_path(item.url) }}">{{ item.excerptTitle|default(item.title) }}</a>
    {% endfor %}

**Arguments**:

- **uuid**: *string* - UUID of the page for which to show the navigation
- **context**: *string* - optional: context to filter navigation
- **depth**: *int* - optional: depth to load (1 - one level deep, 2 - two levels deep, ...). Default to 1.
- **level**: *int|null* - optional: level to load pages on the specific level null for current level. Default to null.
- **properties**: *array<string, string>* - Which properties to load. Default to "title" and "url".

**Returns**:

.. include:: _navigation_structure.inc
