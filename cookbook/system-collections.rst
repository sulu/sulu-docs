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
            # system collection
            my_key:
                meta_title:
                    en: 'Title'
                    de: 'Titel'

                # optional you can also configure sub collections
                collections:
                    my_child_key:
                        meta_title:
                            en: 'Child'
                            de: 'Kind'

This structure will be used to create a Collection Structure like this:

.. code-block:: bash

    System
     |--> Title
     |     |--> Child

If you want todo this in a bundle you need to use the PrependExtensionInterface
of Symfony for it and register there the System collections:

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
                            'my_key' => [
                                'meta_title' => [
                                    'en' => 'Title',
                                    'de' => 'Titel',
                                ],
                                'collections' => [
                                    'my_child' => [
                                        'meta_title' => [
                                            'en' => 'Child',
                                            'de' => 'Kind',
                                        ],
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
(`Sulu\Component\Media\SystemCollections\SystemCollectionManagerInterface`) service
which will by first access create the new collection in your database.

.. code-block:: php

    <?php

    // to get id of system collection
    $systemCollectionManager->getSystemCollection('my_key');

    // to get id of a child system collection
    $systemCollectionManager->getSystemCollection('my_key.my_child_key');

    // to determine if id is a system collection (e.g. validation)
    $systemCollectionManager->isSystemCollection(1);

.. note::

    The key of sub collection is a combination with the parent key so its `parent_key.child_key`
    e.g.: `my_key.my_child_key`.
