PreviewBundle
=============

The PreviewBundle provides the feature of preview for custom-entities.
It is build on top of the route-bundle and can only be introduced for
entities which have a `RouteDefaultsProvider`.

This Provider will be used to determine which controller should be
used to render the HTML of the entity.

PreviewObjectProvider
*********************

The `PreviewObjectProvider` is the interface which will be used
to load, bind and de/serialize the object.

.. code-block:: php

    <?php

    namespace Event\Preview;

    use Sulu\Bundle\PreviewBundle\Preview\Object\PreviewObjectProviderInterface;

    class EventObjectProvider implements PreviewObjectProviderInterface
    {
        public function getObject($id, $locale)
        {
            return ...; // load the object by id
        }

        public function getId($object)
        {
            return $object->getId();
        }

        public function setValues($object, $locale, array $data)
        {
            ... // bind data-array to the object
        }

        public function setContext($object, $locale, array $context)
        {
            ... // context change is for example a template change (e.g. in pages or articles)
        }

        public function serialize($object)
        {
            return serialize($object);
        }

        public function deserialize($serializedObject, $objectClass)
        {
            return unserialize($serializedObject);
        }
    }

After registering a service with this class and the tag
`<tag name="sulu_preview.object_provider" provider-key="events"/>`
you should be able to see the live-preview in your form.
