``sulu_breadcrumb``
===================

Returns the breadcrumb for a given node UUID

**Example**:

.. code-block:: jinja

    {% for item in sulu_breadcrumb(uuid) %}
        <a href="{{ sulu_content_path(item.url) }}">{{ item.title }}</a>
    {% endfor %}

**Arguments**:

- **uuid**: *string* - UUID of page node for which to show the breadcrumb

**Returns**:

- `array`
     - **id**: ID of page
     - **title**: Title of page
     - **url**: URL for page
     - **nodeType**: Type of node
     - **excerpt**: Excerpt
