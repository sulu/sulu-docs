``sulu_resolve_media``
======================

Returns resolved media with needed properties for a given media object.

.. code-block:: jinja

    {% set media = sulu_resolve_media(contact.avatar, 'de') %}
    <img src="{{ media.thumbnails['100x100'] }}" title="{{ media.title }}" />

**Arguments**:

- **media**: *object|int* - The media object or media id.
- **locale**: *string* - Locale to resolve metadata.

**Returns**: *object* - Object with all needed properties, like `thumbnails`,
                        `title`, `description` and `url`.
