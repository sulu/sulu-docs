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

        public function getWebspace(): string
        {
            return 'my_webspace';
        }
    }

The service is automatically tagged via autoconfiguration. If not using autoconfiguration:

.. code-block:: yaml

    services:
        App\Routing\CustomWebspaceRouteGenerator:
            tags:
                - { name: sulu_route.webspace_route_generator, webspace: my_webspace }
