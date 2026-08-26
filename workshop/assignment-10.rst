Assignment 10 — Add, edit, and delete locations in the admin
############################################################

You already have a **list**; now you complete **CRUD** for ``Location`` in the Sulu **JavaScript** admin: **GET/PUT/POST/DELETE**
API, **form** metadata, and **add** / **edit** views in ``LocationAdmin``.

.. note::

   Full code from ``assignment/10``.

Step 1 — ``sulu_admin`` resource with detail route
==================================================

.. code-block:: yaml

   # config/packages/sulu_admin.yaml (excerpt)
   sulu_admin:
       resources:
           locations:
               routes:
                   list: app.get_location_list
                   detail: app.get_location

``detail`` must point to the same **resource** the form uses to load and save a single record (PUT/POST/DELETE on the
same path pattern as in ``EventController``).

Step 2 — ``LocationController`` (full surface)
==============================================

The reference implements the same structure as the workshop’s ``EventController``: **get**, **put**, **post**,
**delete**, and **cget** (list). Data is a flat JSON object with the same keys as the **form** and list.

.. code-block:: php

    // src/Controller/Admin/LocationController.php (excerpt)
    #[Route(path: '/admin/api/locations/{id}', methods: ['GET'], name: 'app.get_location')]
    public function getAction(int $id): Response
    {
        $location = $this->load($id);
        if (!$location instanceof Location) {
            throw new NotFoundHttpException();
        }

        return $this->json($this->getDataForEntity($location));
    }

    #[Route(path: '/admin/api/locations', methods: ['GET'], name: 'app.get_location_list')]
    public function getListAction(): Response
    {
        $listRepresentation = $this->doctrineListRepresentationFactory
            ->createDoctrineListRepresentation(Location::RESOURCE_KEY);

        return $this->json($listRepresentation->toArray());
    }

    // putAction, postAction, deleteAction map mapDataToEntity() / getDataForEntity() ...

**Mapping** in the reference (names match the form below):

.. code-block:: php

    protected function getDataForEntity(Location $entity): array
    {
        return [
            'id' => $entity->getId(),
            'name' => $entity->getName() ?? '',
            'street' => $entity->getStreet(),
            'number' => $entity->getNumber(),
            'postalCode' => $entity->getPostalCode(),
            'city' => $entity->getCity(),
            'countryCode' => $entity->getCountryCode(),
        ];
    }

Step 3 — Form XML
=================

.. code-block:: xml

    <!-- config/forms/location_details.xml (reference) -->
    <key>location_details</key>
    <properties>
        <property name="name" type="text_line" mandatory="true">...</property>
        <property name="street" type="text_line" colspan="9">...</property>
        <property name="number" type="text_line" colspan="3">...</property>
        <property name="postalCode" type="text_line" colspan="3">...</property>
        <property name="city" type="text_line" colspan="6">...</property>
        <property name="countryCode" type="single_select" colspan="3">
            <params>
                <param
                    name="values"
                    type="expression"
                    value="service('App\\Service\\CountryCodeSelect').getValues()"
                />
            </params>
        </property>
    </properties>

The workshop provides ``CountryCodeSelect`` for a fixed select list; you can replace that with a plain **text_line** for
**country code** if you want a smaller exercise.

Step 4 — ``LocationAdmin`` add / edit graph
===========================================

.. code-block:: php

    // src/Admin/LocationAdmin.php (excerpt)
    $addFormView = $this->viewBuilderFactory
        ->createResourceTabViewBuilder(self::LOCATION_ADD_FORM_VIEW, '/locations/add')
        ->setResourceKey('locations')
        ->setBackView(self::LOCATION_LIST_VIEW);
    $viewCollection->add($addFormView);

    $addDetailsFormView = $this->viewBuilderFactory
        ->createFormViewBuilder(self::LOCATION_ADD_FORM_VIEW . '.details', '/details')
        ->setResourceKey('locations')
        ->setFormKey('location_details')
        ->setEditView(self::LOCATION_EDIT_FORM_VIEW)
        ->setParent(self::LOCATION_ADD_FORM_VIEW);
    $viewCollection->add($addDetailsFormView);

    $editFormView = $this->viewBuilderFactory
        ->createResourceTabViewBuilder(self::LOCATION_EDIT_FORM_VIEW, '/locations/:id')
        ->setResourceKey('locations')
        ->setBackView(self::LOCATION_LIST_VIEW)
        ->setTitleProperty('title');
    $viewCollection->add($editFormView);

**Note:** The list view ``setAddView`` and ``setEditView`` calls must point to the correct view names so the add
button and row **Edit** actions open the form overlay.

See also
========

* :doc:`../book/extend-admin`
* :doc:`assignment-11` (``Location`` on ``Event`` with ``single_location_selection``)

Reference branch
================

``assignment/10``.
