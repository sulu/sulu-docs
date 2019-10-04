Reference Store
===============

The reference-store is a service which collects ids of entities/documents
which are used to render a page. This ids will be used for example in the
caching component :doc:`../http_cache`.

Architecture
------------

Each type of content registers its own service which implements the
``ReferenceStoreInterface`` or with the default implementation
``Sulu\Bundle\PageBundle\ReferenceStore\ReferenceStore``.

The service ``sulu_website.reference_store_pool`` collects the services with the
tag ``sulu_website.reference_store`` and use the ``alias`` attribute to
identify them.

To register a loaded entity use the concrete store (e.g.
``sulu_page.reference_store.content`` or your own service) and call the
method ``add`` to append the id of the entity.

Example
-------

.. code-block:: xml

    <service id="app.reference_store.example"
             class="Sulu\Bundle\WebsiteBundle\ReferenceStore\ReferenceStore">
        <tag name="sulu_website.reference_store" alias="example"/>
    </service>

.. code-block:: php

    $exampleReferenceStore = $container->get('app.reference_store.example');
    $exampleReferenceStore->add(1);

    $referenceStore = $container->get('sulu_website.reference_store');
    var_dump($referenceStore->getStore('example')->getAll());

    // prints
    // array(1) {
    //   [0] =>
    //   int(1)
    // }
