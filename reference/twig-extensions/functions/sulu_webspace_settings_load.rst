``sulu_webspace_settings_load``
===============================

Returns the published :doc:`webspace settings <../../../book/webspaces>` of a webspace, or ``null`` as long as
nothing is published.

.. code-block:: jinja

    {% set settings = sulu_webspace_settings_load() %}
    {{ settings.content.receiverEmail }}

    {% set settings = sulu_webspace_settings_load({'mail': 'receiverEmail'}) %}
    {{ settings.mail }}

    {% set settings = sulu_webspace_settings_load(webspaceKey: 'blog', locale: 'de') %}

**Arguments**:

- **properties**: *array<string, string>* - optional: Map of a name in the result to the name of the property in the
  form, whose values become top level keys of the result. By default all properties are resolved into ``content``.
- **webspaceKey**: *string* - optional: The webspace to load the settings of, defaults to the current webspace.
- **locale**: *string* - optional: The locale to load the settings in, defaults to the current localization.

**Returns**:

- `array|null`
    - **content**: Settings content data
    - **view**: Settings view data
