TrashBundle
===========

The TrashBundle implements the trash functionality that is used by most built-in Sulu entities.
It allows to store entities into a trash item and to restore them from the trash at a later point
in time. The bundle adds a trash view that allows the user to restore and remove trash items via
the administration interface.

Integrating the TrashBundle with a custom entity
------------------------------------------------

The TrashBundle is built in an extensible way and provides extension points for integrating
the trash functionality with a custom entity. Doing this involves the following steps:

Store the entity to the trash before deletion
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Use the TrashManager to create a new trash item before your entity is deleted. Mind that the bundle does not know what data of the entity needs to be stored into the trash item yet.

Create a trash item for an entity
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Restore an entity from a trash item
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

(Optional) Add restore configuration for your entity
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

(Optional) Cleanup external data when a trash item is removed
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
