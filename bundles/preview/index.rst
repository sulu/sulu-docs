PreviewBundle
=============

The PreviewBundle provides the feature of preview for custom-entities.
It is build on top of the route-bundle and can only be introduced for
entities which have a `RouteDefaultsProvider`.

This Provider will be used to determine which controller should be
used to render the HTML of the entity. This HTML should contain the
definition `property="..."` which will be used to find the place
where the content takes place. See the documentation for that in
:doc:`/cookbook/live-preview`.

Example
-------

This is a simple (and not complete) example of a news-entity form.

Sulu-Admin form
***************

.. code-block:: js

    define(['services/sulupreview/preview'], function(Preview) {

        return {

            header: { ... },

            layout: function() {
                return {
                    content: {
                        width: 'fixed',
                        leftSpace: true,
                        rightSpace: true
                    },
                    sidebar: (!!this.options.id) ? 'max' : false
                }
            },

            initialize: function() {
                this.render();

                this.sandbox.on('sulu.toolbar.save', this.save.bind(this));
                this.preview.bindDomEvents(this.$el);
            },

            render: function() { ... },

            save: function(action) { ... },

            loadComponentData: function() {
                if (!this.options.id) {
                    return {};
                }

                return this.sandbox.util.load(this.options.url).done(function(data) {
                    this.preview = Preview.initialize({});
                    this.preview.start(
                        'AppBundle\\Entity\\News', // your entity-class
                        this.options.id,
                        this.options.locale,
                        data
                    );

                    return data;
                });
            }
        };

When binding DOM Events by calling `this.preview.bindDomeEvents(this.$el)`
you need to add the class `preview-update` to your elements.

PreviewObjectProvider
*********************

The `PreviewObjectProvider` is the interface which will be used
to load, bind and de/serialize the object.

.. code-block:: php

    <?php

    namespace News\Preview;

    use Sulu\Bundle\PreviewBundle\Preview\Object\PreviewObjectProviderInterface;

    class NewsObjectProvider implements PreviewObjectProviderInterface
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
`<tag name="sulu_preview.object_provider" class="AppBundle\Entity\News"/>`
you should be able to see the live-preview in your form.
