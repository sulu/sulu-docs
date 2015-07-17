``sulu_resolve_medias``
=======================

Returns a full media for a ID or entity. The result contains the full URL and thumbnails.

.. code-block:: jinja

    {% set medias = sulu_resolve_medias(medias, 'de') %}

Or in combination with `sulu_resolve_user`:

.. code-block:: jinja

    {% for media in sulu_resolve_media(sulu_resolve_user(creator).medias[0], 'de') %}
        <a href="{{ media.url }}"><img src="{{ media.thumbnails['170x170'] }}"/></a>
    {% endfor %}

**Arguments**:

- **medias**: *object[]* or *int[]* - The media objects or ids
- **locale**: *string* - locale to load metadata (title, etc.)

**Returns**: *media[]* - full media objects
