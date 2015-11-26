System-Collections
==================

System-Collections are special collections which are not editable, deletable or
movable. Apart from that they can be used like all other collections.

The System takes care of creating and upgrading them. Each bundle can request
them, to save there images like avatar or logos in the contact section.

Because of the usage of the configuration tree also the App itself can register
system collection and use them.

.. code-block:: yaml

    sulu_media:
        system_collections:

            # Prototype
            key:
                meta_title:           []
                collections:

                    # Prototype
                    key:
                        meta_title:           []

This structure will be used to create a Collection Structure like this:

.. code-block:: bash

    System
     |--> Sulu contact
     |     |--> People
     |     |--> Orginizations
     |--> Client Website
           |--> My own System-Collection

To register own System-Collections you can prepend the configuration with your
bundle extension:

.. code-block:: php

    <?php

    class ClientWebsiteExtension extends Extension implements PrependExtensionInterface
    {
        /**
         * {@inheritdoc}
         */
        public function prepend(ContainerBuilder $container)
        {
            if ($container->hasExtension('sulu_media')) {
                $container->prependExtensionConfig(
                    'sulu_media',
                    [
                        'system_collections' => [
                            'client_website' => [
                                'meta_title' => ['en' => 'Client website', 'de' => 'Client Website'],
                                'collections' => [
                                    'uploads' => [
                                        'meta_title' => ['en' => 'My own System-Collection', 'de' => 'Meine eigene System-Collection'],
                                    ],
                                ],
                            ],
                        ],
                    ]
                );
            }
        }

        /**
         * {@inheritdoc}
         */
        public function load(array $configs, ContainerBuilder $container)
        {
            ...
        }
    }

To use this new Collection you can use the `sulu_media.system_collections.manager`
service.

.. code-block:: php

    <?php

    // to get id of system collection
    $systemCollectionManager->getSystemCollection('client_website.uploads');

    // to determine if id is a system collection (e.g. validation)
    $systemCollectionManager->isSystemCollection(1);

.. note::
    The key of the system-collection consists of `namespace.key`. In this case
    `namespace = client_website` and `key = uploads`.
