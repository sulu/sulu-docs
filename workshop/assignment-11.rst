Assignment 11 — Link events to locations (Doctrine + admin selection)
#####################################################################

Replace free-text location fields with a **foreign key** to ``Location``. Editors pick a row from the **Locations**
resource; the admin API reads and writes **locationId** in JSON.

.. note::

   Reference branch ``assignment/11``. The assignment text may say ``src/Controller/EventController.php``; the workshop
   implementation lives in ``App\Controller\Admin\EventController``.

Step 1 — Doctrine association on ``Event``
===========================================

.. code-block:: php

    // src/Entity/Event.php (excerpt)
    #[ORM\ManyToOne(targetEntity: Location::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Location $location = null;

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

Remove any legacy **string** ``location`` column if you no longer need it, then run ``doctrine:schema:update`` or a
migration.

Step 2 — Serialize ``locationId`` in the admin API
====================================================

The Sulu form will submit ``locationId`` as a scalar. Mirror that in your PHPStan type and in **get**/**map**:

.. code-block:: php

    // src/Controller/Admin/EventController.php (excerpt)
    /**
     * @return EventData
     */
    protected function getDataForEntity(Event $entity): array
    {
        $location = $entity->getLocation();

        return [
            // ... title, image, dates ...
            'locationId' => null !== $location ? $location->getId() : null,
        ];
    }

    /**
     * @param EventData $data
     */
    protected function mapDataToEntity(array $data, Event $entity): void
    {
        // ...
        $entity->setLocation(
            $data['locationId'] ? $this->locationRepository->findById((int) $data['locationId']) : null
        );
    }

Step 3 — ``event_details`` form: ``single_location_selection``
==============================================================

.. code-block:: xml

    <!-- config/forms/event_details.xml (excerpt) -->
    <property name="locationId" type="single_location_selection" mandatory="true" colspan="6">
        <meta>
            <title>app.location</title>
        </meta>
    </property>

(Remove the old ``text_line`` for location, if it existed.)

Step 4 — ``field_type_options`` in ``sulu_admin``
=================================================

The type name ``single_location_selection`` must be declared under ``field_type_options.single_selection`` and point
to the **same** **resource** you built in assignments 9–10:

.. code-block:: yaml

   # config/packages/sulu_admin.yaml (excerpt)
   sulu_admin:
       field_type_options:
           single_selection:
               single_location_selection:
                   default_type: list_overlay
                   resource_key: locations
                   types:
                       list_overlay:
                           adapter: table
                           list_key: locations
                           display_properties:
                               - name
                           icon: fa-home
                           empty_text: 'app.location.no_selections'
                           overlay_title: 'app.locations'

.. tip::

   To inspect the merged config:

   .. code-block:: bash

      $ bin/adminconsole debug:config sulu_admin field_type_options.single_selection

See also
========

* :doc:`../book/extend-admin`
* `Doctrine ManyToOne mapping <https://www.doctrine-project.org/projects/doctrine-orm/en/latest/reference/association-mapping.html#many-to-one-bidirectional>`__

Reference branch
================

``assignment/11`` — also compare ``config/forms/event_details.xml`` and translations for new labels.
