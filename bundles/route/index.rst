RouteBundle
===========

The RouteBundle provides routing for custom entities in Sulu. Routes are stored
in the database and matched dynamically via the Symfony CMF routing chain.

Key features:

- **Database-driven routes**: Routes stored in the ``ro_routes`` table
- **Webspace support**: Native multi-tenancy via the webspace field
- **Automatic history**: Old URLs automatically redirect (301) to new ones
- **Schema-based generation**: Use expressions to define URL patterns
- **Hierarchical routes**: Parent-child relationships for nested URLs

Routes link to your entities via ``resourceKey`` (entity type identifier) and
``resourceId`` (entity identifier). This decouples routing from your entity structure.

Further Topics
--------------

.. toctree::
    :maxdepth: 1

    extension_points

Route Model
-----------

The ``Route`` model (``Sulu\Route\Domain\Model\Route``) represents a route in the system:

.. list-table::
    :header-rows: 1

    * - Property
      - Type
      - Description
    * - id
      - int
      - Auto-increment primary key
    * - webspace
      - ?string
      - Webspace key for multi-tenancy (null for global routes)
    * - locale
      - string
      - Route locale (e.g., ``en``, ``de``)
    * - slug
      - string
      - URL path (e.g., ``/events/my-event``)
    * - parentRoute
      - ?Route
      - Parent route for hierarchical URLs
    * - resourceKey
      - string
      - Entity type identifier (e.g., ``events``)
    * - resourceId
      - string
      - Entity identifier (as string)

Example with Custom Entity
--------------------------

Routes are stored independently from your entities. They reference your entity
via ``resourceKey`` (a string identifier for the entity type) and ``resourceId``
(the entity's ID as a string).

Entity
******

Your entity does not need to implement any interface for routing:

.. code-block:: php

    namespace App\Entity;

    class Event
    {
        private ?int $id = null;
        private string $title;
        private string $locale;

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getTitle(): string
        {
            return $this->title;
        }

        public function getLocale(): string
        {
            return $this->locale;
        }
    }

The ``resourceKey`` for this entity would typically be ``events`` (defined in your
admin configuration), and the ``resourceId`` would be the entity's ID.

Route Schema
************

The route schema is defined in the XML template of the route property:

.. code-block:: xml

    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">

        ...

        <properties>
            <property name="title" type="text_line" mandatory="true">
                <meta>
                    <title lang="en">Title</title>
                </meta>

                <tag name="sulu.rlp.part"/>
            </property>

            <property name="url" type="route" mandatory="true">
                <meta>
                    <title lang="en">Resourcelocator</title>
                    <title lang="de">Adresse</title>
                </meta>

                <params>
                    <param name="route_schema" value="/{translator.trans('event', [], 'admin')}/{implode('-', object)}"/>
                </params>

                <tag name="sulu.rlp"/>
            </property>
        </properties>
    </template>

The ``route_schema`` parameter defines the URL pattern using Symfony's ExpressionLanguage.

Route Parts
^^^^^^^^^^^

Properties tagged with ``<tag name="sulu.rlp.part"/>`` are automatically included in the
``object`` variable. This allows you to reference content properties in your route schema:

.. code-block:: xml

    <property name="title" type="text_line">
        <tag name="sulu.rlp.part"/>
    </property>

    <property name="year" type="text_line">
        <tag name="sulu.rlp.part"/>
    </property>

With these properties tagged, you can use them in the route schema:

.. code-block:: xml

    <param name="route_schema" value="/blog/{object.year}/{object.title}"/>

Available Variables
^^^^^^^^^^^^^^^^^^^

``object``
    An array-like object containing the entity's route-relevant properties (the "parts").
    Supports both dot notation and array access:

    - ``object.title`` - dot notation
    - ``object['title']`` - array access

``translator``
    The Symfony translator service for creating localized URL segments:

    - ``translator.trans('event')`` - translate with default domain
    - ``translator.trans('event', [], 'messages')`` - translate with specific domain

``locale``
    The current locale string (e.g., ``en``, ``de``).

Available Functions
^^^^^^^^^^^^^^^^^^^

``implode(separator, object)``
    Joins all parts with the given separator:

    .. code-block:: xml

        <param name="route_schema" value="/{implode('-', object)}"/>

    With parts ``['year' => '2024', 'title' => 'Hello']`` this produces ``/2024-hello``.

``is_array(value)``
    Checks if a value is an array. Useful for conditional logic in expressions.

.. note::

    All path segments are automatically cleaned up (slugified) based on the locale.
    The ``PathCleanup`` service handles special characters, diacritics, and
    locale-specific replacements.

EventController
***************

.. code-block:: php

    namespace App\Controller;

    use App\Entity\Event;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;

    class EventController extends AbstractController
    {
        public function indexAction(Event $event): Response
        {
            return $this->render('events/event.html.twig', ['event' => $event]);
        }
    }

RouteDefaultsProvider
*********************

The RouteDefaultsProvider maps a route to controller defaults. Implement
``RouteDefaultsProviderInterface`` and provide the ``resourceKey``:

.. code-block:: php

    namespace App\Routing;

    use App\Entity\Event;
    use App\Repository\EventRepository;
    use Sulu\Route\Application\Routing\Matcher\RouteDefaultsProviderInterface;
    use Sulu\Route\Domain\Model\Route;
    use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

    class EventRouteDefaultsProvider implements RouteDefaultsProviderInterface
    {
        public function __construct(
            private EventRepository $eventRepository,
        ) {
        }

        public function getDefaults(Route $route): array
        {
            $event = $this->eventRepository->find((int) $route->getResourceId());

            if (null === $event) {
                throw new NotFoundHttpException();
            }

            return [
                '_controller' => EventController::class . '::indexAction',
                'event' => $event,
            ];
        }

        public static function getResourceKey(): string
        {
            return 'events';
        }
    }

The service is automatically tagged via autoconfiguration when implementing
``RouteDefaultsProviderInterface``. If not using autoconfiguration:

.. code-block:: yaml

    services:
        App\Routing\EventRouteDefaultsProvider:
            tags:
                - { name: sulu_route.route_defaults_provider, resource_key: events }

Route History
-------------

When a route's slug changes, the old URL is automatically preserved as a history route
that redirects (301) to the new URL. This happens via Doctrine event listeners.

History routes have:

- ``resourceKey = 'route_histories'``
- ``resourceId = '{originalResourceKey}::{originalResourceId}'``

When a user visits an old URL:

1. The CMF router matches the history route
2. The ``RouteHistoryDefaultsProvider`` resolves the target route
3. A 301 redirect is returned to the new URL

If the target route no longer exists, a 410 Gone response is returned.

Generating URLs
---------------

To generate URLs for routes, use the ``RouteGeneratorInterface``:

.. code-block:: php

    use Sulu\Route\Application\Routing\Generator\RouteGeneratorInterface;

    class EventController extends AbstractController
    {
        public function __construct(
            private RouteGeneratorInterface $routeGenerator,
        ) {
        }

        public function showAction(Event $event): Response
        {
            $url = $this->routeGenerator->generate(
                slug: '/events/my-event',
                locale: 'en',
                webspace: 'example',
            );

            // $url = 'https://example.com/en/events/my-event'
        }
    }
