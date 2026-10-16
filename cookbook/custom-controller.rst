Pass additional data to your template using a custom controller
===============================================================

Templates include a ``controller`` tag that defines which controller is used for rendering pages of the template.
Sulu includes the ``Sulu\Content\UserInterface\Controller\Website\ContentController`` that resolves the data of the
properties of the template and passes it to your twig template (see :doc:`../book/twig` for the variables).
If you want to pass additional data to your twig template, you can configure a custom controller class that extends it.

.. code-block:: xml

    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">

        ...
        <controller>App\Controller\Website\CustomController::indexAction</controller>
        ...
    </template>

Sulu sets the ``_controller`` of the route of a page or an article to the ``controller`` tag of the template.
It also sets the route defaults ``object`` and ``view``, which are the arguments of the ``indexAction``:

- ``object``: The content of the requested locale in the live stage (``DimensionContentInterface``).
- ``view``: The ``view`` tag of the template.

Overwrite only the ``resolveSuluParameters`` method to add data for your twig template, not the ``indexAction``.
The preview of the administration interface uses the same controller with the draft content (``object`` is then the
draft and ``preview`` is ``true``), so the method runs on draft content too.

.. code-block:: php

    <?php

    declare(strict_types=1);

    namespace App\Controller\Website;

    use App\Service\EventProvider;
    use Sulu\Content\Domain\Model\DimensionContentInterface;
    use Sulu\Content\UserInterface\Controller\Website\ContentController;

    class CustomController extends ContentController
    {
        public function __construct(private readonly EventProvider $eventProvider)
        {
        }

        protected function resolveSuluParameters(
            DimensionContentInterface $object,
            string $webspaceKey,
            bool $normalize,
        ): array {
            $parameters = parent::resolveSuluParameters($object, $webspaceKey, $normalize);
            $parameters['events'] = $this->eventProvider->getUpcomingEvents();

            return $parameters;
        }
    }

The additional data is available in the template as ``events``.

The skeleton registers the classes in ``src/`` as services and autowires their constructor, so inject services there.
To use a service without constructor injection, add it with ``getSubscribedServices`` like described in the
`Service Subscribers Documentation`_. Call ``parent::getSubscribedServices()`` there, because ``ContentController``
adds services it needs itself.

.. _Service Subscribers Documentation: https://symfony.com/doc/current/service_container/service_subscribers_locators.html
