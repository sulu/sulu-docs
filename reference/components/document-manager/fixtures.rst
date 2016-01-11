Data Fixtures
=============

The Sulu DocumentManager integration includes a fixture loader which allows
you to load static data into your content repository.

Getting Started
---------------

Shown below is the simple data fixtures:

.. code-block:: php

    <?php
    // YourBundle/DataFixtures/Document/SomeFixture.php

    namespace YourBundle\DataFixtures\Document;

    use Sulu\Component\DocumentManager\DocumentManager;
    use Sulu\Bundle\DocumentManagerBundle\DataFixtures\DocumentFixtureInterface;

    class SomeFixture implements DocumentFixtureInterface
    {
        public function getOrder()
        {
            return 10;
        }

        public function load(DocumentManager $documentManager)
        {
            $document = $documentManager->create('page');
            // ...
            $documentManager->persist($document);
            $documentManager->flush();
        }
    }

Note that:

- The class name MUST end with `Fixture` for it to be recognized
- The class MUST be placed in `<your bundle>/DataFixtures/Document` in order
  for it to be loaded automatically.

You can now execute your data fixture using the
``sulu:document:fixtures:load``
command.

.. code-block:: bash

    $ php app/console sulu:document:fixtures:load

By default this command will purge and re-initialize the workspace before
loading all of the fixtures.

.. warning::

    Unless you use the `--append` option, your workspace will be purged!

Advanced Usage
--------------

You can specify directories instead of having the command automatically find
the fixtures:

.. code-block:: bash

    $ php app/console sulu:document:fixtures:load --fixtures=/path/to/fixtures1 --fixtures=/path/to/fixtures2

You can also specify if fixturs should be *appended* (i.e. the repository will
not be purged) and if the initializer should be executed.

Append fixtures:

.. code-block:: bash

    $ php app/console sulu:document:fixtures:load --append

Do not initialize:

.. code-block:: bash

    $ php app/console sulu:document:fixtures:load --no-initialize

Using the Service Container
---------------------------

If you need the service container you can implement the `Symfony\Component\DependencyInjection\ContainerAwareInterface`:

.. code-block:: php

    <?php
    // YourBundle/DataFixtures/Document/SomeFixture.php

    namespace YourBundle\DataFixtures\Document;

    use Sulu\Bundle\DocumentManagerBundle\DataFixtures\DocumentFixtureInterface;
    use Symfony\Component\DependencyInjection\ContainerAwareInterface;
    use Symfony\Component\DependencyInjection\ContainerInterface;

    class SomeFixture implements DocumentFixtureInterface implements ContainerAwareInterface
    {
        private $container;

        public function setContainer(ContainerInterface $container = null)
        {
            $this->container = $container;
        }
    }
