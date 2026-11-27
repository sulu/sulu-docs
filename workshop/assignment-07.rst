Assignment 7 — Display event registrations in the Sulu admin
############################################################

Event managers need to see **who registered** for each event **inside the Sulu admin**, not only in the database. You add
a **REST** list endpoint, **resource** config, a **list** definition (XML), and a **child list view** on the event edit
screen that passes ``eventId`` to the API.

.. note::

   The snippets match ``assignment/07``.

What you learn
==============

* Sulu **resources**: mapping a **resource key** to named **routes** in ``config/packages/sulu_admin.yaml``.
* **List metadata** in ``config/lists/`` and how the admin list view consumes it.
* ``ListRouteBuilder`` and ``addRouterAttributesToListRequest`` to scope a list by a **parent** id. See
  :doc:`../book/extend-admin`.

Prerequisites
=============

* Assignment 6 (``EventRegistration`` exists and the website writes rows).

Step 1 — REST list controller
=============================

.. code-block:: php

    // src/Controller/Admin/EventRegistrationController.php
    #[Route(path: '/admin/api/events/{eventId}/registrations', methods: ['GET'], name: 'app.get_event_registration_list')]
    public function getListAction(int $eventId): Response
    {
        $listRepresentation = $this->doctrineListRepresentationFactory->createDoctrineListRepresentation(
            EventRegistration::RESOURCE_KEY,
            ['eventId' => (string) $eventId],
        );

        return $this->json($listRepresentation->toArray());
    }

Register this controller in your **admin** routing (e.g. ``config/routes_admin.yaml``) like the other ``/admin/api``
routes.

**Entity** side: the workshop adds ``RESOURCE_KEY`` and an **identity** column so the list can filter on the owning
**event** (``eventId``). The list metadata maps that in ``config/lists/event_registrations.xml``:

.. code-block:: xml

    <!-- config/lists/event_registrations.xml (excerpt) -->
    <identity-property name="eventId" visibility="never">
        <field-name>event</field-name>
        <entity-name>App\Entity\EventRegistration</entity-name>
    </identity-property>

The visible columns in the reference are **firstName**, **lastName**, and **email**.

Step 2 — ``sulu_admin`` resource
================================

.. code-block:: yaml

   # config/packages/sulu_admin.yaml
   sulu_admin:
       resources:
           event_registrations:
               routes:
                   list: app.get_event_registration_list

(``events`` block remains as before.)

Step 3 — New tab on the event edit form
=======================================

In ``EventAdmin::configureViews``, a second **list** view is **parented** on the event **edit** view, **resource key**
``EventRegistration::RESOURCE_KEY``, and **router** attributes map ``:id`` from the URL to ``eventId`` for the list
request:

.. code-block:: php

    // src/Admin/EventAdmin.php (excerpt)
    $registrationsListView = $this->viewBuilderFactory->createListViewBuilder(
        self::EVENT_EDIT_FORM_VIEW . '.registrations',
        '/registrations',
    )
        ->setResourceKey(EventRegistration::RESOURCE_KEY)
        ->setListKey(self::EVENT_REGISTRATION_LIST_KEY)
        ->setTabTitle('app.registrations')
        ->addRouterAttributesToListRequest(['id' => 'eventId'])
        ->addListAdapters(['table'])
        ->addToolbarActions([])
        ->setUserSettingsKey(EventRegistration::RESOURCE_KEY)
        ->setParent(self::EVENT_EDIT_FORM_VIEW);
    $viewCollection->add($registrationsListView);

After ``cache:clear``, open **Events** → an event → **Registrations** tab. You should see the row submitted from the
website.

See also
========

* :doc:`../book/extend-admin`
* `JsonResponse in Symfony <https://symfony.com/doc/current/controller.html#returning-a-json-response>`__

Reference branch
================

``assignment/07``.
