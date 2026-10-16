``sulu_resolve_user``
=====================

Returns the user entity for the given id, or ``null`` if the id is not an integer or no user exists.

.. code-block:: jinja

    {{ sulu_resolve_user(userId).contact.fullName }}

**Arguments**:

- **id**: *int* - Id of the requested user.

**Returns**: *User|null* - Object with all needed properties.
