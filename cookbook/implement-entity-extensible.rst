How to implement an entity extensible?
======================================

Sulu uses the PersistenceBundle to provide an easy way to replace or extend entities.
In this tutorial we will implement our own extensible book entity.

1. Entity
---------

We will start with the entity itself. Our extensible entity builds up upon two classes:

BookInterface
"""""""""""""
``(Sulu\Bundle\BookBundle\Entity\BookInterface, Interface)``

Defines the Interface of our entity and is used as type for variables of the entity.
Every entity which extends or replaces our book entity must implement this interface to ensure compatibility with
the rest of the system.

Book
""""
``(Sulu\Bundle\BookBundle\Entity\Book, implements BookInterface)``

Implements the book entity and is the base class for extending the entity.
This class is our default entity implementation and is mapped as mapped-superclass in `Book.orm.xml`.

.. note::

    To ensure full exchangeability, it is mandatory to use `BookInterface` as type of every variable,
    doctrine relationship and other usage of our book entity.

2. Repository
-------------

Additionally to our entity classes, we need two repository classes to handle our entities:

BookRepositoryInterface
"""""""""""""""""""""""
``(Sulu\Bundle\BookBundle\Entity\BookRepositoryInterface, Interface, extends RepositoryInterface)``

Defines the Interface of our repository and is used as type for variables of the `BookRepository`.
An interface for an extensible entity extends the `RepositoryInterface` of the PersistenceBundle.

The `RepositoryInterface` defines a `createNew()` method which must be used to create new instances
of an entity instead of the constructor. It is necessary to use the method of the repository for instance creation,
to avoid creating instances of wrong entity implementations when the entity implementation is changed.

BookRepository
""""""""""""""
``(Sulu\Bundle\BookBundle\Entity\BookRepository, implements BookRepositoryInterface, optionally extends EntityRepository)``

Implements the concrete repository for our entity. It is recommended that this class extends the
EntityRepository class of the PersistenceBundle, which implements a dynamic `createNew()` method and will
always return a new instance of the currently configured entity implementation.

.. note::

    To ensure full exchangeability, it is mandatory to use `BookRepositoryInterface` as type of every variable
    which holds an instance of our `BookRepository`.

3. Configuration
----------------

Finally we need to adjust three configuration files to register our entity as extensible.

After configuration, the PersistenceBundle will automatically set the following parameters/services to our container:

* `sulu.model.book.class`: currently set entity implementation (Parameter)
* `sulu.repository.book`: currently set repository implementation (Service)
* `Sulu\Bundle\BookBundle\Entity\BookRepositoryInterface`: alias for the currently set repository implementation (Service)

``DependencyInjection/Configuration.php``
"""""""""""""""""""""""""""""""""""""""""

In the `Configuration.php` file we set our default entity and repository implementation. These implementations are used
if no other bundle replaces or extends our entity.
We implemented the class `Book` as our default entity and the class `BookRepository` as our default repository,
therefore our configuration looks something like the following code block.

.. code-block:: php

    <?php
    class Configuration implements ConfigurationInterface
    {
        public function getConfigTreeBuilder()
        {
            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root('sulu_book')
                (...)
                ->children()
                    ->arrayNode('objects')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->arrayNode('book')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('model')->defaultValue('Sulu\Bundle\BookBundle\Entity\Book')->end()
                                    ->scalarNode('repository')->defaultValue('Sulu\Bundle\BookBundle\Entity\BookRepository')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end();

            return $treeBuilder;
        }
        (...)
    }

This results in the configuration path `sulu_book.objects.book.model` for the model class and
`sulu_book.objects.book.repository` for the repository class.
These paths can be used to overwrite the used implementations.

``DependencyInjection/SuluBookExtension.php``
"""""""""""""""""""""""""""""""""""""""""""""

In the `SuluBookExtension.php` file we need to read the set configuration and define and map the respective services
to the container. Additionally, we add the repository interface as alias for the configured repository implementation
to make the repository autowireable.
We use the already implemented  `configurePersistence()` method of the `PersistenceExtensionTrait` class and the
and the `addAliases()` method of the `ContainerBuilder` to do this.
Therefore our `SuluBookExtension.php` will look something like this:

.. code-block:: php

    <?php
    class SuluBookExtension extends Extension
    {
        use PersistenceExtensionTrait;

        public function load(array $configs, ContainerBuilder $container)
        {
            $configuration = new Configuration();
            $config = $this->processConfiguration($configuration, $configs);
            (...)
            $this->configurePersistence($config['objects'], $container);
            $container->addAliases(
                [
                    'Sulu\Bundle\BookBundle\Entity\BookRepositoryInterface' => 'sulu.repository.book',
                ],
            );
        }
        (...)
    }

``SuluBookBundle.php``
""""""""""""""""""""""

In the `SuluBookBundle.php` file we need to add a compiler pass to automatically resolve our interface to
the configured entity implementation when used in a doctrine mapping.
To do this, we use the already implemented `buildPersistence()` method of the `PersistenceBundleTrait` class.
After this our `SuluBookBundle.php` will look something like this:

.. code-block:: php

    <?php
    class SuluBookBundle extends Bundle
    {
        use PersistenceBundleTrait;

        public function build(ContainerBuilder $container)
        {
            (...)
            $this->buildPersistence(
                [
                    'Sulu\Bundle\BookBundle\Entity\BookInterface' => 'sulu.model.book.class',
                ],
                $container
            );
        }
        (...)
    }
