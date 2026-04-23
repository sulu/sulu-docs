``sulu_page_navigation_root_tree``
==================================

Returns the navigation as a tree of pages:

**Example**:

.. code-block:: twig

    <ul>
        {% for item in sulu_page_navigation_root_tree('main', 2) %}
            <li>
                <a href="{{ sulu_content_path(item.url) }}">{{ item.title }}</a>
            </li>

            {% if item.children|length != 0 %}
                <ul>
                    {% for child in item.children %}
                        <li>
                            <a href="{{ sulu_content_path(child.url) }}">{{ child.title }}</a>
                        </li>
                    {% endfor %}
                </ul>
            {% endif %}
        {% endfor %}
    </ul>

**Complex Example**:

.. code-block:: twig

    <ul>
        {% for item in sulu_page_navigation_root_tree('main', 2, {
            'title': 'title',
            'url': 'url',
            'excerptTitle': 'excerpt.title',
            'uuid': 'object.resource.uuid',
        }) %}
            <li>
                <a href="{{ sulu_content_path(item.url) }}">{{ item.excerptTitle|default(item.title) }}</a>
            </li>

            {% if item.children|length != 0 %}
                <ul>
                    {% for child in item.children %}
                        <li>
                            <a href="{{ sulu_content_path(child.url) }}">{{ child.excerptTitle|default(child.title) }}</a>
                        </li>
                    {% endfor %}
                </ul>
            {% endif %}
        {% endfor %}
    </ul>

**Arguments**:

- **context**: *string* - optional: context to filter navigation
- **depth**: *int* - optional: depth to load (1 - one level deep, 2 - two levels deep, ...). Default to 1.
- **properties**: *array<string, string>* - Which properties to load. Default to "title" and "url".

**Returns**:

.. include:: _navigation_structure.inc
