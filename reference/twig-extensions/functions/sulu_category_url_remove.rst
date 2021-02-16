.. _sulu_category_url_remove:

``sulu_category_url_remove``
============================

Returns current URL and removes given category from GET parameters.

**Arguments**:

- **category**: *array* - Serialized Category instance to determine value
- **categoryParameter**: *string* - optional "category": parameter name

**Returns**: string - current URL without given category in categories parameter

**See also**:

- :ref:`sulu_category_url`
- :ref:`sulu_category_url_append`
- :ref:`sulu_category_url_toggle`
- :ref:`sulu_category_url_clear`
