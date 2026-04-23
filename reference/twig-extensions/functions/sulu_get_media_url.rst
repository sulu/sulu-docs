``sulu_get_media_url``
======================

Returns relative URL to the given media.

.. code-block:: jinja

    {% set url = sulu_get_media_url(media, 'inline') %}

**Arguments**:

- **media**: *object* - The media object
- **dispositionType**: *string* - override default configuration ('inline', 'attachment') **(optional)**

**Returns**: *string* - Relative URL
