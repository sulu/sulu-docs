Assignment 12 — Filter the events overview by location
######################################################

**Smart content** (assignment 5) is not enough when you need **query-string** filters and a custom **POST-free** form on
the public site. This task **removes** the ``events`` smart field from the ``event_overview`` template, **points** the
template to a custom **controller** that extends Sulu’s **DefaultController**, and loads **events** + **locations** from
Doctrine.

.. note::

   The reference is ``assignment/12``.

Step 1 — ``EventRepository::filterByLocationId``
================================================

.. code-block:: php

    // src/Repository/EventRepository.php (excerpt)
    /**
     * @return Event[]
     */
    public function filterByLocationId(?int $locationId, string $locale): array
    {
        $criteria = ['enabled' => true];
        if ($locationId) {
            $criteria['location'] = $locationId;
        }

        $events = $this->findBy($criteria);
        foreach ($events as $event) {
            $event->setLocale($locale);
        }

        return $events;
    }

* When ``$locationId`` is ``null`` (no query parameter), the workshop lists **all** **enabled** events.
* The ``location`` key in the criteria must match the **association** name on ``Event`` (``ManyToOne`` to
  ``Location`` from assignment 11).

Step 2 — Custom page controller
===============================

.. code-block:: php

    // src/Controller/Website/EventOverviewController.php
    <?php

    declare(strict_types=1);

    namespace App\Controller\Website;

    use App\Repository\EventRepository;
    use App\Repository\LocationRepository;
    use Sulu\Bundle\WebsiteBundle\Controller\DefaultController;
    use Sulu\Component\Content\Compat\StructureInterface;

    class EventOverviewController extends DefaultController
    {
        protected function getAttributes($attributes, StructureInterface $structure = null, $preview = false)
        {
            $eventRepository = $this->container->get(EventRepository::class);
            $locationRepository = $this->container->get(LocationRepository::class);
            $request = $this->getRequest();
            $locationId = $request->query->get('location');

            $attributes = parent::getAttributes($attributes, $structure, $preview);
            $attributes['events'] = $eventRepository->filterByLocationId(
                $locationId ? (int) $locationId : null,
                $request->getLocale(),
            );
            $attributes['locations'] = $locationRepository->findAll();

            return $attributes;
        }

        public static function getSubscribedServices(): array
        {
            return \array_merge(
                parent::getSubscribedServices(),
                [
                    EventRepository::class,
                    LocationRepository::class,
                ],
            );
        }
    }

``DefaultController`` is not Symfony’s usual `AbstractController`_ (constructor injection is not used the same way);
instead you subscribe **services** with ``getSubscribedServices()``. Always **merge** with
``parent::getSubscribedServices()`` and call **parent::getAttributes** so ``content`` and the rest of the Sulu view
model stay intact.

.. _AbstractController: https://symfony.com/doc/current/controller.html

Step 3 — Wire the template to the new controller
================================================

.. code-block:: xml

    <!-- config/templates/pages/event_overview.xml (excerpt) -->
    <view>pages/event_overview</view>
    <controller>App\Controller\Website\EventOverviewController::indexAction</controller>

**Remove** the entire ``<property name="events" type="smart_content">…`` block—events now come from PHP, not the PHPCR
content.

Step 4 — Twig: GET form + loop over ``events``
==============================================

.. code-block:: twig

    <!-- templates/pages/event_overview.html.twig (excerpt, assignment/12) -->
    <form action="{{ sulu_content_path(content.url) }}" method="get" class="col-3">
        <div class="form-group">
            <label for="location">Location</label>
            <select id="location" name="location" class="form-control">
                <option value>All ...</option>
                {% for location in locations %}
                    <option value="{{ location.id }}"
                            {% if app.request.get('location') == location.id %}selected{% endif %}>
                        {{ location.name }}
                    </option>
                {% endfor %}
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <div class="row">
        {% for event in events %}
            <div class="col-lg-4 text-center">
                <h2 class="event-title">{{ event.title }}</h2>
                <p>{{ event.teaser }}</p>
                <p>
                    <a class="btn btn-secondary" href="{{ path('app.event', {id: event.id}) }}">View details »</a>
                </p>
            </div>
        {% endfor %}
    </div>

Note: after switching from **smart content** to **entity** data, the ``Event`` object may expose **title** and
**teaser** directly (via your entity’s translation layer); use ``{{ dump(events) }}`` during development to match the
workshop version.

.. tip::

   The query parameter is named **location** in the reference (``?location=2``) to match ``$request->query->get('location')``.

See also
========

* :doc:`../cookbook/custom-controller`
* :doc:`../book/twig`

Reference branch
================

``assignment/12`` — diff ``config/templates/pages/event_overview.xml``, ``EventRepository.php``, and
``EventOverviewController.php`` against your branch.
