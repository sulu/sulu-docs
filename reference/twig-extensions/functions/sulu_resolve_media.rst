``sulu_resolve_media``
======================

Returns a full media for a ID or entity. The result contains the full URL and thumbnails.

.. code-block:: jinja

    {% set media = sulu_resolve_media(media, 'de') %}

Or in combination with `sulu_resolve_user`:

.. code-block:: jinja

    {% set media = sulu_resolve_media(sulu_resolve_user(creator).medias[0], 'de') %}
    <a href="{{ media.url }}"><img src="{{ media.thumbnails['170x170'] }}"/></a>

**Arguments**:

- **media**: *object* or *int* - The media object or id
- **locale**: *string* - locale to load metadata (title, etc.)

**Returns**: *media[]* - full media objects
