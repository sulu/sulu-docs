``sulu_resolves_media.rst``
===========================

Returns resolved medias with needed properties for a given media array.

.. code-block:: jinja

    {% set medias = sulu_resolve_medias(contact.medias, 'de') %}
    {% for media in medias %}
        <img src="{{ media.thumbnails['100x100'] }}" title="{{ media.title }}" />
    {% endfor %}

**Arguments**:

- **media**: *object[]* - The media object.
- **locale**: *string* - Locale to resolve metadata.

**Returns**: *object[]* - Object with all needed properties, like `thumbnails`, `title`, `description` and `url`.
