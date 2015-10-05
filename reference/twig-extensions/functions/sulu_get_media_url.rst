``sulu_get_media_url``
======================

Returns relative URL to the given media.

.. code-block:: jinja

    {% set url = sulu_get_media_url(media, 'inline') %}

**Configuration**:

Following configuration is optional and means, that the default dispositionType is "attachment" for each file and only if the mimeTypes of a file match "application/pdf" or "image/jpeg" it's the "inline" dispositionType. 

If the default dispositionType would be "inline" and some files should be "attachment", than the configuration of "mime_types_attachment" should be filled and "mime_types_inline" should be empty.

.. code-block:: yaml

  sulu_media:
    disposition_type:
      default: "attachment"
      mime_types_inline: ["application/pdf", "image/jpeg"]
      mime_types_attachment: []

**Arguments**:

- **media**: *object* - The media object
- **dispositionType**: *string* - override default configuration ('inline', 'attachment') **(optional)**

**Returns**: *string* - Relative URL
