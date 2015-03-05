``content_path``
================

Returns the absolute URL for the content at the given path

.. code-block:: jinja

    <ul class="nav nav-justified">
        {% for item in content.snippets[0].internalLinks %}
            <li>
                <a href="{{ content_path(item.url, item.webspaceKey) }}" title="{{ item.title }}">{{ item.title }}</a>
            </li>
        {% endfor %}
    </ul>

**Arguments**:

- **url**: *string* - Url to get path
- **webspaceKey** *string* - If item is not in the same webspace as current content (**optional**)

**Returns**: *string* - Absolute URL

