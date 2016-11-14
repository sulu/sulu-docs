Custom Route-Generator
======================

The RouteBundle enables you to define custom route generators.
These generators will be called every time you create or update
a route for the given entity. For each entity you can define
which generator will be used.

The following example will generate a route by using the
core route generator, but you can define route schemas for
each entity type.

.. code-block:: php

    <?php

    class RouteGenerator implements RouteGeneratorInterface
    {
        /**
         * @var RouteGeneratorInterface
         */
        private $routeGenerator;

        /**
         * {@inheritdoc}
         */
        public function __construct(RouteGeneratorInterface $routeGenerator)
        {
            $this->routeGenerator = $routeGenerator;
        }

        /**
         * {@inheritdoc}
         */
        public function generate($entity, array $options)
        {
            $type = $entity->getType();

            if (!array_key_exists($type, $options)) {
                throw new \Exception(sprintf('Route-schema for type "%s" not configured!', $type));
            }

            return $this->routeGenerator->generate($entity, ['route_schema' => $options[$type]]);
        }

        /**
         * {@inheritdoc}
         */
        public function getOptionsResolver(array $options)
        {
            // allow all options.
            return (new OptionsResolver())->setDefined(array_keys($options));
        }
    }

Register this as a service with the tag `sulu.route_generator` (with alias `custom`
for example) and use this alias in the configuration:

.. code-block:: yaml

    sulu_route:
        mappings:
            AppBundle\Entity\Recipe:
                generator: schema
                options:
                    route_schema: /{translator.trans('recipe')}/{object.getTitle()}
            AppBundle\Entity\Example:
                generator: custom
                options:
                    type1: /example/{object.getName()}
                    type2: /example/{object.getTitle()}
