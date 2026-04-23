``sulu_content_path``
=====================

Returns the URL for the content at the given path. It is recommended to
use this function instead of outputting a URL directly, especially when
working with articles, as it ensures the correct URL is generated for
the current webspace and locale.

.. code-block:: jinja

    <ul class="nav nav-justified">
        {% for item in content.snippets[0].internalLinks %}
            <li>
                <a href="{{ sulu_content_path(item.url, item.webspaceKey) }}" title="{{ item.title }}">{{ item.title }}</a>
            </li>
        {% endfor %}
    </ul>

**Arguments**:

- **url**: *string* - Url to get path
- **webspaceKey** *string* - If item is not in the same webspace as current
  content (**optional**)
- **locale** *string* - If item is not in the same locale as current
  content (**optional**)

**Returns**: *string* - URL
