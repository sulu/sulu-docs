PreviewBundle
=============

The PreviewBundle implements the preview feature for pages and custom entities.

The bundle is built to be compatible with the RouteBundle. For custom entities, the
`RouteDefaultsProvider` will be used to determine which controller should be
used to render the HTML of an entity.

Configuration
-------------

The PreviewBundle allows for the following configuration:

.. code-block:: yaml

    # config/packages/sulu_admin.yaml
    sulu_preview:
        mode: 'auto' # can be set to 'off' to disable the preview
        cache_adapter: 'cache.app' # where the preview cache is stored

PreviewObjectProvider
---------------------

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

        public function getSecurityContext($id, $locale): ?string
        {
            return null; // the security context used in the admin class for this object
        }
    }

Afterwards the services has to be registered using this class and the tag
`<tag name="sulu_preview.object_provider" provider-key="events"/>`.

PreviewFormViewBuilder
----------------------

In order to display the preview in our form, we have to make use of the `PreviewFormViewBuilder` in the Admin class.

.. note::

    For more information about Admin Class take a look at :doc:`../../book/extend-admin`.

.. code-block:: php

    <?php

    namespace App\Admin;

    use App\Entity\Event;
    use Sulu\Bundle\AdminBundle\Admin\Admin;
    use Sulu\Bundle\AdminBundle\Admin\View\ToolbarAction;
    use Sulu\Bundle\AdminBundle\Admin\View\ViewBuilderFactoryInterface;
    use Sulu\Bundle\AdminBundle\Admin\View\ViewCollection;

    class EventAdmin extends Admin
    {
        const EVENT_FORM_KEY = 'event_details';
        const EVENT_EDIT_FORM_VIEW = 'app.event_edit_form';

        public function __construct(private ViewBuilderFactoryInterface $viewBuilderFactory)
        {
        }

        public function configureViews(ViewCollection $viewCollection): void
        {
            $editFormView = $this->viewBuilderFactory
                ->createResourceTabViewBuilder(static::EVENT_EDIT_FORM_VIEW, '/events/:id')
                ->setResourceKey(Event::RESOURCE_KEY)
                ->setBackView(static::EVENT_LIST_VIEW);

            $viewCollection->add($editFormView);

            $editDetailsFormView = $this->viewBuilderFactory
                ->createPreviewFormViewBuilder(static::EVENT_EDIT_FORM_VIEW . '.details', '/details')
                ->setPreviewCondition('id != null') // this is an optional condition when the preview should be shown
                ->setResourceKey(Event::RESOURCE_KEY)
                ->setFormKey(static::EVENT_FORM_KEY)
                ->setTabTitle('sulu_admin.details')
                ->addToolbarActions([new ToolbarAction('sulu_admin.save'), new ToolbarAction('sulu_admin.delete')])
                ->setParent(static::EVENT_EDIT_FORM_VIEW);

            $viewCollection->add($editDetailsFormView);
        }
    }

Preview Cache
-------------

The preview use a cache to improve its performance. By default it will use the configured
symfony ``cache.app`` adapter.

You can configure other adapter the following way:

.. code-block:: yaml

    # config/packages/sulu_admin.yaml
    sulu_preview:
        cache_adapter: "cache.app" # symfony cache adapter id

For example if you want to use redis you can do it this way:

.. code-block:: yaml

    # config/packages/sulu_admin.yaml
    sulu_preview:
        cache_adapter: "cache.adapter.redis"

    framework:
        cache:
            default_redis_provider: 'redis://localhost' # this is default and not needed

If you are in a multi server setup its recommended to set the whole `cache.app` in your
`config/packages/cache.yaml` to a central cache like redis.

.. code-block:: yaml

    # config/packages/cache.yaml
    framework:
        cache:
            default_redis_provider: '%env(REDIS_CACHE)%' # REDIS_CACHE can be set in your .env files
            app: cache.adapter.redis
            # prefix_seed: 'my_project_%kernel.environment%' # unique name for the project installation to avoid cache conflicts between multiple installations

Read more about it in the `Symfony Cache Documentation`_.

.. _Symfony Cache Documentation: https://symfony.com/doc/4.4/cache.html#configuring-cache-with-frameworkbundle
