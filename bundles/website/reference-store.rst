Reference Store
===============

The reference store is a service which collects ids of entities used to render
a page. These ids are used for cache tag generation in the caching component
:doc:`../http_cache`.

Architecture
------------

Sulu provides a single ``ReferenceStoreInterface`` service
(``Sulu\Bundle\HttpCacheBundle\ReferenceStore\ReferenceStoreInterface``) that
stores references for all resource types. Each reference consists of an entity
id and a resource key.

Usage
-----

Inject the ``ReferenceStoreInterface`` and call ``add`` with the entity id and
resource key to register a loaded entity:

.. code-block:: php

    use Sulu\Bundle\HttpCacheBundle\ReferenceStore\ReferenceStoreInterface;

    class MyService
    {
        public function __construct(
            private ReferenceStoreInterface $referenceStore,
        ) {
        }

        public function loadExample(string $id): void
        {
            // ... load entity ...

            $this->referenceStore->add($id, 'examples');
        }
    }
