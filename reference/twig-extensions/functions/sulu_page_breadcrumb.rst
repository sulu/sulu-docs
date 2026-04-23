``sulu_page_breadcrumb``
========================

Returns the breadcrumb for a given page uuid as a list of pages:

**Example**:

.. code-block:: twig

    {% for item in sulu_page_breadcrumb(resource.uuid) %}
        <a href="{{ sulu_content_path(item.url) }}">{{ item.title }}</a>
    {% endfor %}

**Complex Example**:

.. code-block:: twig

    {% for item in sulu_page_breadcrumb(resource.uuid, {
        'title': 'title',
        'url': 'url',
        'excerptTitle': 'excerpt.title',
        'uuid': 'object.resource.uuid',
    }) %}
        <a href="{{ sulu_content_path(item.url) }}">{{ item.excerptTitle|default(item.title) }}</a>
    {% endfor %}

**Arguments**:

- **uuid**: *string* - UUID of page node for which to show the breadcrumb
- **properties**: *array<string, string>* - Which properties to load. Default to "title" and "url".

**Returns**:

.. include:: _navigation_structure.inc

.. note::

    For articles using the ``page_tree_route`` property type, the breadcrumb of the
    assigned parent page can be rendered by passing the page UUID from the article's
    URL object:

    .. code-block:: twig

        {% for item in sulu_page_breadcrumb(view.url.page.uuid) %}
            <a href="{{ sulu_content_path(item.url) }}">{{ item.title }}</a>
        {% endfor %}