``sulu_resolve_contact``
========================

Returns user entity.

.. code-block:: jinja

    {{ sulu_resolve_contact(contactId).fullName }}

**Arguments**:

- **id**: *int* - Id of the requested contact.

**Returns**: *Contact* - Object with all needed properties.
