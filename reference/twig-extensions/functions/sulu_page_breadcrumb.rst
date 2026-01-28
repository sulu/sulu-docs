``sulu_page_breadcrumb``
========================

Returns the breadcrumb for a given page uuid as a list of pages:

**Example**:

.. code-block:: twig

    {% for item in sulu_page_breadcrumb(uuid) %}
        <a href="{{ sulu_content_path(item.url) }}">{{ item.title }}</a>
    {% endfor %}

**Complex Example**:

.. code-block:: twig

    {% for item in sulu_page_breadcrumb(uuid, {
        'title': 'title',
        'url': 'url',
        'excerptTitle': 'excerpt.title',
    }) %}
        <a href="{{ sulu_content_path(item.url) }}">{{ item.excerptTitle|default(item.title) }}</a>
    {% endfor %}

**Arguments**:

- **uuid**: *string* - UUID of page node for which to show the breadcrumb
- **properties**: *array<string, string>* - Which properties to load. Default to "title" and "url".

**Returns**:

.. include:: _navigation_structure.inc
