``sulu_breadcrumb``
===================

Returns the breadcrumb for a given node UUID

.. code-block:: jinja

    {% for item in sulu_breadcrumb(uuid) %}
        <a href="{{ sulu_content_path(item.url) }}">{{ item.title }}</a>
    {% endfor %}

**Arguments**:

- **uuid**: *string* - UUID of page node for which to show the breadcrumb
