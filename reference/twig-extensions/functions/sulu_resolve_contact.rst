``sulu_resolve_contact``
========================

Returns the contact entity for the given id, or ``null`` if no contact exists.

.. code-block:: jinja

    {{ sulu_resolve_contact(author).fullName }}

**Arguments**:

- **id**: *int|string* - Id of the requested contact.

**Returns**: *Contact|null* - Object with all needed properties.
