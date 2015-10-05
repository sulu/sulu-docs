``sulu_categories``
===================

Returns all categories in the system.

.. code-block:: jinja

    <ul>
        <li><a href="{{ sulu_category_url_clear() }}">None</a></li>
        {% for category in sulu_categories() %}
            <li id="{{ category.key }}">
                <a href="{{ sulu_category_url(category) }}">{{ category.name }}</a>
            </li>
        {% endfor %}
    </ul>

**Returns**: array - array of serialized Category instances
