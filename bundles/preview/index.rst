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

Navigate from Preview to Block
-------------------------------

Hovering a block inside the preview shows a small focus button.
Clicking it scrolls the admin form to the matching block and expands it.

Rendering the Deep Link in Twig
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Call the ``sulu_preview_deep_link()`` Twig function on the root element of your block template, passing
it the block's id:

.. code-block:: twig

    {# includes/blocks/text_image.html.twig #}
    <div {{ sulu_preview_deep_link(block._id) }}>
        <h3>{{ content.title }}</h3>
        {# ... #}
    </div>

The function renders a ``data-sulu-preview-id`` attribute in the preview. For previews rendered by the
standard ``ContentController``, everything else - the click handling in the preview iframe and the
scroll/expand behaviour in the admin form - is already wired up by the bundle; there is nothing else to
configure.

If a custom ``RouteDefaultsProvider`` or controller renders the preview, include the
``preview-deep-link.js`` bridge script in its Twig template as well. The standard controller loads this
script through ``@SuluWebsite/Preview/preview.html.twig``; custom templates do not load it automatically.
The script is available at
``src/Sulu/Bundle/WebsiteBundle/Resources/public/js/preview-deep-link.js`` in sulu/sulu.

.. note::

    ``block._id`` is only set while ``block_id_generator`` is enabled for the block field, which
    is the default for page, article and snippet templates. If you disabled it, or are rendering a
    block field that predates this option, ``sulu_preview_deep_link()`` simply renders nothing and
    the block won't be clickable from the preview.

Headless Setup
~~~~~~~~~~~~~~

With the HeadlessBundle, the JSON returned while previewing already includes each block's id -
for example, a block inside ``homeBlocks`` looks like this while previewing (the ``id`` key is
omitted outside of a preview render):

.. code-block:: json

    {
        "type": "text-image",
        "settings": [],
        "id": "0198f2b1-2e3a-7000-8a1b-2c9e6f8d1a4b",
        "title": "Why Sulu"
    }

Sulu has no control over how or where your frontend renders, so getting a click from your preview
back to the admin is on your frontend. The frontend must:

#. Render the id as a ``data-sulu-preview-id`` attribute on the block's root DOM element - the
   same role ``sulu_preview_deep_link()`` plays in Twig.

#. On click of an element carrying that attribute, ``postMessage`` the admin window with
   ``{type: 'sulu.preview.navigate', id: <the id>}``:

   .. code-block:: javascript

       var adminWindow = window.opener || window.parent;

       document.addEventListener('click', function (event) {
           var anchor = event.target.closest('[data-sulu-preview-id]');
           if (anchor) {
               adminWindow.postMessage(
                   {type: 'sulu.preview.navigate', id: anchor.getAttribute('data-sulu-preview-id')},
                   '*'
               );
           }
       });

When the headless frontend is hosted on a different origin from the admin, a direct message to the
admin is rejected. In this case, serve a preview wrapper from the admin's origin. The frontend sends
its message to this wrapper, which relays it to the admin. The HeadlessBundle preview implementation
uses this wrapper pattern.

For a reference implementation of the click handling, see
``src/Sulu/Bundle/WebsiteBundle/Resources/public/js/preview-deep-link.js`` in sulu/sulu - the script
the classic Twig integration loads automatically.
