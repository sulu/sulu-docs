.. _sulu_category_url_toggle:

``sulu_category_url_toggle``
============================

Returns current URL and adds given category as GET parameter, if it's not already there
or removes given category from GET parameters, if it is.

**Arguments**:

- **category**: *array* - Serialized Category instance to determine value
- **categoryParameter**: *string* - optional "category": parameter name

**Returns**: string - current URL with or without given category in categories parameter

**See also**:

- :ref:`sulu_category_url`
- :ref:`sulu_category_url_append`
- :ref:`sulu_category_url_remove`
- :ref:`sulu_category_url_clear`
