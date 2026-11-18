Extension Points
================

The RouteBundle provides several extension points for customizing route behavior.

RouteDefaultsProvider
---------------------

The most common extension point. Implement ``RouteDefaultsProviderInterface`` to map
routes to controller defaults for your custom entities.

See the :doc:`main RouteBundle documentation </bundles/route/index>` for a complete example.

.. code-block:: php

    namespace App\Routing;

    use Sulu\Route\Application\Routing\Matcher\RouteDefaultsProviderInterface;
    use Sulu\Route\Domain\Model\Route;

    class MyRouteDefaultsProvider implements RouteDefaultsProviderInterface
    {
        public function getDefaults(Route $route): array
        {
            return [
                '_controller' => MyController::class . '::indexAction',
                'entity' => $this->loadEntity($route->getResourceId()),
            ];
        }

        public static function getResourceKey(): string
        {
            return 'my_entities';
        }
    }

The service is automatically tagged when implementing the interface. The ``resourceKey``
returned by ``getResourceKey()`` must match the ``resourceKey`` of routes you want to handle.

Webspace Route Generator
------------------------

Implement ``WebspaceRouteGeneratorInterface`` to customize URL generation for specific webspaces.
This is useful when different webspaces have different URL structures or domains.

.. code-block:: php

    namespace App\Routing;

    use Sulu\Route\Application\Routing\Generator\WebspaceRouteGeneratorInterface;
    use Symfony\Component\Routing\RequestContext;

    class CustomWebspaceRouteGenerator implements WebspaceRouteGeneratorInterface
    {
        public function generate(
            RequestContext $context,
            string $slug,
            string $locale,
        ): string {
            // Custom URL generation logic for this webspace
            return 'https://custom-domain.com/' . $locale . $slug;
        }

        public static function getWebspace(): string
        {
            return 'my_webspace';
        }
    }

The service is tagged automatically via autoconfiguration. The webspace comes from the static
``getWebspace()`` method, which is not part of the interface. Without the method, set the ``webspace``
attribute of the tag. If you do not use autoconfiguration, add the tag yourself:

.. code-block:: yaml

    services:
        App\Routing\CustomWebspaceRouteGenerator:
            tags:
                - { name: sulu_route.webspace_route_generator, webspace: my_webspace }

RouteCollectionForRequestLoader
-------------------------------

Implement ``RouteCollectionForRequestLoaderInterface`` to match routes of a request which
are not stored in the ``ro_routes`` table. The loaders are asked one after the other and
the first one which returns a non-empty ``RouteCollection`` wins.

.. code-block:: php

    namespace App\Routing;

    use Sulu\Route\Application\Routing\Matcher\RouteCollectionForRequestLoaderInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Route;
    use Symfony\Component\Routing\RouteCollection;

    class MyRouteCollectionLoader implements RouteCollectionForRequestLoaderInterface
    {
        public function getRouteCollectionForRequest(Request $request): RouteCollection
        {
            $routeCollection = new RouteCollection();

            // add a route only if the request is handled by this loader
            $routeCollection->add('my_dynamic_route', new Route(
                $request->getPathInfo(),
                defaults: [
                    '_controller' => MyController::class,
                ],
                host: $request->getHost(),
            ));

            return $routeCollection;
        }
    }

The service is automatically tagged via autoconfiguration. If not using autoconfiguration:

.. code-block:: yaml

    services:
        App\Routing\MyRouteCollectionLoader:
            tags:
                - { name: sulu_route.route_collection_for_request_loader, priority: 200 }

A loader with a higher ``priority`` is asked first. The loader for the routes of the
``ro_routes`` table has the priority 100, a tag without ``priority`` has 0 and comes after it.
