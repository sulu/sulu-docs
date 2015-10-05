``sulu_tags``
=============

Returns all tags in the system.

.. code-block:: jinja

    <ul>
        <li><a href="{{ sulu_tag_url_clear() }}">None</a></li>
        {% for tag in sulu_tags() %}
            <li><a href="{{ sulu_tag_url(tag) }}">{{ tag.name }}</a></li>
        {% endfor %}
    </ul>

**Returns**: array - array of serialized Tag instances
