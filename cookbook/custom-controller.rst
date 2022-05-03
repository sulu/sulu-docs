Pass additional data to your template using a custom controller
===============================================================

Templates include a ``controller`` tag that defines which controller is used for rendering pages of the template.
Sulu includes a ``DefaultController`` that resolves the data of the properties of the template and passes it to
your twig template. If you want to pass additional data to your twig template, you can configure a custom controller
class that provides this data.

.. code-block:: xml

    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">

        ...
        <controller>App\Controller\Website\CustomController::indexAction</controller>
        ...
    </template>

Inside of your controller implementation, you are free to harness the full power of the Symfony framework.
If you want to access a service, you need to implement the ``getSubscribedServices`` method like described in the
`Service Subscribers Documentation`_.

In most cases, you want your custom controller to extend the functionality of the ``DefaultController`` of Sulu.
When extending the ``DefaultController``, you only need to overwrite the ``getAttributes`` method to include the data
that should be passed to your twig template.

.. code-block:: php

    <?php

    namespace App\Controller\Website;

    use Sulu\Bundle\WebsiteBundle\Controller\DefaultController;
    use Sulu\Component\Content\Compat\StructureInterface;

    class CustomController extends DefaultController
    {
        protected function getAttributes($attributes, StructureInterface $structure = null, $preview = false)
        {
            $attributes = parent::getAttributes($attributes, $structure, $preview);
            $attributes['myData'] = $this->get('my_custom_service')->getMyData();

            return $attributes;
        }

        public static function getSubscribedServices(): array
        {
            $subscribedServices = parent::getSubscribedServices();
            $subscribedServices['my_custom_service'] = 'your_service_id_or_class';

            return $subscribedServices;
        }
    }

.. _Service Subscribers Documentation: https://symfony.com/doc/current/service_container/service_subscribers_locators.html
