``sulu_page_navigation_is_active``
==================================

Returns whether a navigation item is active for the request path. An item is active if its path equals the request
path, or occurs in it followed by ``/`` or the end of the path. ``/products`` is active for ``/products``,
``/products/shoes`` and ``/en/products``, but not for ``/products-old``.

The item path is not anchored at the start of the request path, so ``/products`` is also active for ``/old/products``.

**Example**:

.. code-block:: twig

    {% for item in sulu_page_navigation_root_flat('main') %}
        {% set active = sulu_page_navigation_is_active(app.request.pathInfo, item.url) %}
        <a href="{{ sulu_content_path(item.url) }}" class="{{ active ? 'active' : '' }}">{{ item.title }}</a>
    {% endfor %}

**Arguments**:

- **requestPath**: *string* - Path of the current request, e.g. ``app.request.pathInfo``
- **itemPath**: *string* - Path of the navigation item, e.g. ``item.url``

**Returns**: *bool* - ``true`` if the item is active
