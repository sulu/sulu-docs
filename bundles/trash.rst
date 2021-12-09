TrashBundle
===========

The ``TrashBundle`` implements the trash functionality that is used by most built-in Sulu entities.
It allows to store entities into a trash item and to restore them from the trash at a later point
in time. Furthermore, the bundle adds a trash view that allows the user to restore and remove
trash items via the administration interface.

The bundle uses the `TrashItem entity`_ to store the data of entities that were moved into the trash.
``TrashItem`` entities contain all data that is necessary to restore the original entity and
are identified by a ``resourceKey`` and a ``resourceId``.
To manage these entities, the bundle provides a `TrashManager service`_ that allows to create
new trash items and restore and delete existing trash items.

Integrating the TrashBundle with a custom entity
------------------------------------------------

The ``TrashBundle`` is built in an extensible way and provides extension points for integrating
the trash functionality with a custom entity. The `TrashManager service`_ uses these extension points
of the bundle to automatically call the correct handlers for given entities and trash items.

Integrating the ``TrashBundle`` with a custom entity involves the following steps:

Store the entity to the trash before deletion
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Use the ``TrashManager`` service to create a new ``TrashItem`` entity before you delete your entity.
The ``TrashManager`` service will create the ``TrashItem`` entity by calling the registered handler
for the given entity. We will implement this handler for your custom entity in the next steps.

.. code-block:: php

    $this->trashManager->store(Book::RESOURCE_KEY, $book);

Create a trash item for an entity
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

When creating a new ``TrashItem`` entity, the ``TrashManager`` service looks for a service
that implements the `StoreTrashItemHandlerInterface interface`_ and supports the given entity.

To implement this functionality for your custom entity, you create a ``TrashItemHandler`` service that
implements the ``StoreTrashItemHandlerInterface`` and creates a ``TrashItem`` entity.
The created entity contains all data that is needed to restore our custom entity at a later point
in time.

.. code-block:: php

    <?php

    class BookTrashItemHandler implements StoreTrashItemHandlerInterface {

        public function store(object $book, array $options = []): TrashItemInterface
        {
            return $this->trashItemRepository->create(
                Book::RESOURCE_KEY,
                (string) $book->getId(),
                $book->getTitle(),
                [
                    'title' => $book->getTitle(),
                    'description' => $book->getDescription(),
                    'created' => $book->getCreated()->format('c'),
                ];
                null,
                $options,
                Book::SECURITY_CONTEXT,
                null,
                null
            );
        }

        public static function getResourceKey(): string
        {
            return Book::RESOURCE_KEY;
        }
    }

Restore an entity from a trash item
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

When restoring an existing ``TrashItem`` entity, the ``TrashManager`` service looks for a service
that implements the `RestoreTrashItemHandlerInterface interface`_ and supports the given trash item.

To integrate the restore functionality with your custom entity, you can extend the ``TrashItemHandler``
service created in the previous step to implement the ``RestoreTrashItemHandlerInterface``.
Your service receives the existing ``TrashItem`` entity and should restore the original custom entity
with the correct data.

.. code-block:: php

    <?php

    class BookTrashItemHandler implements StoreTrashItemHandlerInterface, RestoreTrashItemHandlerInterface {

        public function restore(TrashItemInterface $trashItem, array $restoreFormData = []): object
        {
            $data = $trashItem->getRestoreData();

            $book = new Book();
            $book->setTitle($data['title']);
            $book->setDescription($data['description']);
            $book->setCreated(new \DateTime($data['created']));

            $this->entityManager->persist($book);
            $this->entityManager->flush();

            return $book;
        }

        public function store(object $book, array $options = []): TrashItemInterface
        {
            // implemented in a previous step
        }

        public static function getResourceKey(): string
        {
            // implemented in a previous step
        }
    }

(Optional) Add restore configuration for your entity
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

The behaviour of the ``TrashBundle`` integration an entity can be configured by registering a
service that implements the `RestoreConfigurationProviderInterface interface`_.

To do this for your custom entity, you can extend the ``TrashItemHandler`` service created in the
previous steps to implement the ``RestoreConfigurationProviderInterface`` and return a
`RestoreConfiguration object`_ from the ``getConfiguration`` method.

.. code-block:: php

    <?php

    class BookTrashItemHandler implements StoreTrashItemHandlerInterface, RestoreTrashItemHandlerInterface, RestoreConfigurationProviderInterface {

        public function getConfiguration(): RestoreConfiguration
        {
            return new RestoreConfiguration(
                null,
                AlbumAdmin::EDIT_FORM_VIEW,
                ['id' => 'id']
            );
        }

        public function restore(TrashItemInterface $trashItem, array $restoreFormData = []): object
        {
            // implemented in a previous step
        }

        public function store(object $book, array $options = []): TrashItemInterface
        {
            // implemented in a previous step
        }

        public static function getResourceKey(): string
        {
            // implemented in a previous step
        }
    }

The ``RestoreConfiguration`` class allows to set the following configuration properties.
All configuration properties are optional an can be set to ``null``.

.. list-table::
    :header-rows: 1

    * - Property
      - Description
    * - form
      - Defines a form key that should be displayed when a trash item is restored.
        The data of the form will be passed to the ``RestoreTrashItemHandlerInterface::restore``
        method.
        For example, this allows to select a new parent entity when restoring a nested entity
        like a page.
    * - view
      - Defines a view key to which the user is redirected after restoring a trash item.
    * - resultToView
      - Defines which properties should be used as view attributes when redirecting the user to
        the configured view after an entity was restored.
        For example, this allows to set the ``id`` attribute of a route to the identifier of
        the restored entity.
    * - resultSerializationGroups
      - Defines the serialization groups that are used to serialize a restored entity.
        The properties of the serialized entity can be used in the ``resultToView`` attribute.

(Optional) Cleanup external data when a trash item is removed
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

When removing an existing ``TrashItem`` entity, the ``TrashManager`` service looks for a service
that implements the `RemoveTrashItemHandlerInterface interface`_ and supports the given trash item.
If such a service is found, the ``remove`` method of the service is called with the ``TrashItem``
entity that will be removed.

This extension point allows to clean up external data when a trash item is removed and therefore
cannot be restored anymore. For example, this can be used to clean up associated files on the hard
drive or related data in an external system.

.. _TrashItem entity: https://github.com/sulu/sulu/blob/2.x/src/Sulu/Bundle/TrashBundle/Domain/Model/TrashItem.php
.. _TrashManager service: https://github.com/sulu/sulu/blob/2.x/src/Sulu/Bundle/TrashBundle/Application/TrashManager/TrashManager.php
.. _StoreTrashItemHandlerInterface interface: https://github.com/sulu/sulu/blob/2.x/src/Sulu/Bundle/TrashBundle/Application/TrashItemHandler/StoreTrashItemHandlerInterface.php
.. _RestoreTrashItemHandlerInterface interface: https://github.com/sulu/sulu/blob/2.x/src/Sulu/Bundle/TrashBundle/Application/TrashItemHandler/RestoreTrashItemHandlerInterface.php
.. _RemoveTrashItemHandlerInterface interface: https://github.com/sulu/sulu/blob/2.x/src/Sulu/Bundle/TrashBundle/Application/TrashItemHandler/RemoveTrashItemHandlerInterface.php
.. _RestoreConfiguration object: https://github.com/sulu/sulu/blob/2.x/src/Sulu/Bundle/TrashBundle/Application/RestoreConfigurationProvider/RestoreConfiguration.php
.. _RestoreConfigurationProviderInterface interface: https://github.com/sulu/sulu/blob/2.x/src/Sulu/Bundle/TrashBundle/Application/RestoreConfigurationProvider/RestoreConfigurationProviderInterface.php
