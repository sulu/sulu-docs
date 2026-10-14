``sulu_page_navigation_root_flat``
==================================

Returns the navigation as a list of pages:

**Example**:

.. code-block:: twig

    {% for item in sulu_page_navigation_root_flat('main', 2) %}
        <a href="{{ sulu_content_path(item.url) }}">{{ item.title }}</a>
    {% endfor %}

**Complex Example**:

.. code-block:: twig

    {% for item in sulu_page_navigation_root_flat('main', 2, {
        'title': 'title',
        'url': 'url',
        'excerptTitle': 'excerpt.title',
        'uuid': 'object.resource.uuid',
    }) %}
        <a href="{{ sulu_content_path(item.url) }}">{{ item.excerptTitle|default(item.title) }}</a>
    {% endfor %}

**Arguments**:

- **navigationContext**: *string* - Navigation context to load the pages of
- **depth**: *int* - optional: depth to load (1 - one level deep, 2 - two levels deep, ...). Default to 1.
- **properties**: *array<string, string>* - optional: Maps result keys to the properties to load, merged over the
  defaults ``title``, ``url`` and ``targetType``.

**Returns**:

.. include:: _navigation_structure.inc
