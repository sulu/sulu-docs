``sulu_content_root_path``
==========================

Returns the absolute URL for the content root at the given path

.. code-block:: jinja

    <ul class="nav nav-justified">
        {% for item in content.snippets[0].internalLinks %}
            <li>
                <a href="{{ sulu_content_root_path(item.url, item.webspaceKey) }}" title="{{ item.title }}">{{ item.title }}</a>
            </li>
        {% endfor %}
    </ul>

**Arguments**:

- **url**: *string* - Url to get path
- **webspaceKey** *string* - If item is not in the same webspace as current
  content (**optional**)
- **locale** *string* - If item is not in the same locale as current
  content (**optional**)
- **domain** *string* - If a specific domain should be used to generate the url
  (**optional**)
- **scheme** *string* - If a different scheme (as the current scheme) should be
  used to generate the url (**optional**)

**Returns**: *string* - Absolute URL
