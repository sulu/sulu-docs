Preview form
============


PreviewFormViewBuilder
----------------------

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

        /**
         * @var ViewBuilderFactoryInterface
         */
        private $viewBuilderFactory;

        public function __construct(ViewBuilderFactoryInterface $viewBuilderFactory)
        {
            $this->viewBuilderFactory = $viewBuilderFactory;
        }

        public function configureViews(ViewCollection $viewCollection): void
        {
            $editFormView = $this->viewBuilderFactory->createResourceTabViewBuilder(static::EVENT_EDIT_FORM_VIEW, '/events/:id')
                ->setResourceKey(Event::RESOURCE_KEY)
                ->setBackView(static::EVENT_LIST_VIEW);

            $viewCollection->add($editFormView);

            $editDetailsFormView = $this->viewBuilderFactory->createPreviewFormViewBuilder(static::EVENT_EDIT_FORM_VIEW . '.details', '/details')
                ->setPreviewCondition('id != null') // this is an optional condition when the preview should be shown
                ->setResourceKey(Event::RESOURCE_KEY)
                ->setFormKey(static::EVENT_FORM_KEY)
                ->setTabTitle('sulu_admin.details')
                ->addToolbarActions([new ToolbarAction('sulu_admin.save'), new ToolbarAction('sulu_admin.delete')])
                ->setParent(static::EVENT_EDIT_FORM_VIEW);

            $viewCollection->add($editDetailsFormView);
        }
    }

.. note:: For more information about Admin Class take a look at `Extend Admin UI`

.. _Extend Admin UI: ../../book/extend-admin.rst