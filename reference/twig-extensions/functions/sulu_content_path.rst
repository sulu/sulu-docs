``sulu_content_path``
=====================

Returns the absolute URL for the content at the given path. The domain 
is taken from ``app/Resources/webspaces/*.io.xml`` and your current 
environment. In case you have multiple URLs in one environment, you can
prioritize one by giving it ``<url main="true">``.

.. code-block:: jinja

    <ul class="nav nav-justified">
        {% for item in content.snippets[0].internalLinks %}
            <li>
                <a href="{{ sulu_content_path(item.url, item.webspaceKey) }}" 
                   title="{{ item.title }}">{{ item.title }}</a>
            </li>
        {% endfor %}
    </ul>

**Arguments**:

- **url**: *string* - Url to get path
- **webspaceKey** *string* - If item is not in the same webspace as current 
  content (**optional**)

**Returns**: *string* - Absolute URL
