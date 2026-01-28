``sulu_page_navigation_tree``
=============================

Returns the navigation for a given page uuid as a tree of pages:

**Example**:

.. code-block:: twig

    <ul>
        {% for item in sulu_page_navigation_tree(uuid, 'main', 2) %}
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
        {% for item in sulu_page_navigation_tree(uuid, 'main', 2, 2, {
            'title': 'title',
            'url': 'url',
            'excerptTitle': 'excerpt.title',
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

- **uuid**: *string* - UUID of page node for which to show the navigation
- **context**: *string* - optional: context to filter navigation
- **depth**: *int* - optional: depth to load (1 - one level deep, 2 - two levels deep, ...). Default to 1.
- **level**: *int|null* - optional: level to load pages on the specific level null for current level. Default to null.
- **properties**: *array<string, string>* - Which properties to load. Default to "title" and "url".

**Returns**:

.. include:: _navigation_structure.inc
