``sulu_resolve_user``
=====================

Returns user entity.

.. code-block:: jinja

    {{ sulu_resolve_user(changer).contact.fullName }}

**Arguments**:

- **id**: *int* - Id of the requested user.

**Returns**: *User* - Object with all needed properties.
