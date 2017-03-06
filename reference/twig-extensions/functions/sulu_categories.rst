``sulu_categories``
===================

Returns all categories in the system.

.. code-block:: jinja

    <ul>
        <li><a href="{{ sulu_category_url_clear() }}">None</a></li>
        {% for category in sulu_categories('en', 'category_key') %}
            <li id="{{ category.key }}">
                <a href="{{ sulu_category_url(category) }}">{{ category.name }}</a>
            </li>
        {% endfor %}
    </ul>

**Arguments**:

 - **locale**: If item is not in the same locale as current content (**optional**)
 - **parentKey**: If only specific categories should be loaded set a parent category key (**optional**)

**Returns**: array - array of serialized Category instances
