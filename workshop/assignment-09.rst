Assignment 9 — Display the location list in the admin
#####################################################

With ``Location`` persisted, the **admin** needs a **list** and a **navigation** entry. This mirrors the **Event** module
but uses your new ``App\Controller\Admin\LocationController::getListAction`` and ``config/lists/locations.xml``.

.. note::

   The following is taken from ``assignment/09``; assignment 10 adds the **detail** route and forms (see
   :doc:`assignment-10`).

What you learn
==============

* The **get list** + ``DoctrineListRepresentation`` pattern in the workshop (via ``DoctrineListRepresentationFactory``).
* Registering a new **resource** ``locations`` with a **list** route only, then **LocationAdmin** with
  ``createListViewBuilder``.

Key files
=========

1. **Controller (list only in stage 9)**  
   ``getListAction`` on ``/admin/api/locations`` returns ``Location::RESOURCE_KEY`` list (after you add a constant to
   the entity in the same branch as the project expects).

2. **Routes** — ``config/routes_admin.yaml`` (same as other admin API controllers).

3. **sulu_admin**

   .. code-block:: yaml

      # config/packages/sulu_admin.yaml (excerpt, assignment/09+)
      sulu_admin:
          resources:
              locations:
                  routes:
                      list: app.get_location_list

4. **List definition** — ``config/lists/locations.xml`` (columns for ``name``, ``city``, ``countryCode``—see the branch
   for the exact list ``key`` matching the entity and ``RESOURCE_KEY``).

5. **``LocationAdmin``**  
   A new class ``App\Admin\LocationAdmin`` registers a child navigation item under the existing **Events** module in the
   reference, and a **List** view at a path like ``/locations``:

   .. code-block:: php

      // src/Admin/LocationAdmin.php (excerpt)
      $listView = $this->viewBuilderFactory->createListViewBuilder(
          self::LOCATION_LIST_VIEW,
          '/locations',
      )
          ->setResourceKey(Location::RESOURCE_KEY)
          ->setListKey(self::LOCATION_LIST_KEY)
          ->setTitle('app.locations')
          ->addListAdapters(['table'])
          ->addToolbarActions($listToolbarActions);
      $viewCollection->add($listView);

Service registration: if the project autoconfigures ``Admin`` classes, clear cache and the **Locations** item should
 appear.

See also
========

* :doc:`../book/extend-admin`
* :doc:`assignment-10` — completing detail routes and forms

Reference branch
================

``assignment/09``.
