Securing your application
=========================

Sulu is delivered with two different possibilities to protect parts of your
application. The first is the permissions based on security contexts, which
allow you to restrict access to entire parts of your application or Sulu. The
permissions for this kind of security are managed on a roles level. In addition
to that the localization for which these permissions are valid has to be
defined on the assignment of the role to the user.

The second way is to protect the access on a per-object basis. These
permissions are set on the specific object. The user still has to have the
correct localizations assigned in order to gain access.

This tutorial will show how to use Sulu's security functionality with your own
application specific code.

Protect content using a security context
----------------------------------------

This section describes how to protect an entire part of your application (but
not a specific object).

Define your security context
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

First of all you have to define the security context, which is represented by a
simple string. This is done in the ``Admin`` class of your Bundle:

.. code-block:: php
    
    <?php

    namespace Acme\Bundle\ExampleBundle\Admin;

    use Sulu\Bundle\AdminBundle\Admin\Admin;
    use Sulu\Component\Security\Authorization\PermissionTypes;

    class AcmeExampleAdmin extends Admin
    {
        // ...

        public function getSecurityContexts()
        {
            return [
                'Sulu' => [
                    'Acme' => [
                        'sulu.acme.example' => [
                            PermissionTypes::VIEW,
                            PermissionTypes::ADD,
                            PermissionTypes::EDIT,
                            PermissionTypes::DELETE,
                        ],
                    ],
                ],
            ];
        }

        // ...
    }

This information is defined in the ``getSecurityContexts`` method, which should
return an array. The first level describes the system to which the security
context applies - this would either be Sulu (for stuff in the administration)
or a different context that you have defined manually.

The second level just defines the title for another separation used in the
administration interface. The third level defines the name of the permissions
themselves. This name follows a namespacing scheme based on the previously used
names. This value is the key for an array containing all the available
permission types for this security context.

.. note::
    
    Since the ``Admin`` class is registered as a service, you can make use of
    different services to define the available security contexts. For example
    the SuluPageBundle uses a service to create an own security context for
    all available webspaces in the system.

Protect your controller
~~~~~~~~~~~~~~~~~~~~~~~

After defining a security context, you can use it to  protect the actions
of one of your controllers. All you have to do is to implement the
``SecuredControllerInterface`` telling the ``SuluSecurityListener`` which
security context and locale to use for the permission check:

.. code-block:: php

    <?php

    namespace Acme\Bundle\ExampleBundle\Controller;

    use FOS\RestBundle\Routing\ClassResourceInterface;
    use Sulu\Component\Security\SecuredControllerInterface;
    use Symfony\Component\HttpFoundation\Request;

    class ExampleController implements ClassResourceInterface, SecuredControllerInterface
    {
        public function cgetAction()
        {
            // code for your get action
        }

        public function postAction()
        {
            // code for your post action
        }

        // ...

        public function getLocale(Request $request)
        {
            return $request->get('locale');
        }

        public function getSecurityContext()
        {
            return 'sulu.acme.example';
        }
    }

The ``getLocale`` method returns the locale, which is probably determined
somehow by the request, and the ``getSecurityContext`` method defines which
security context is required to access this type of resource.

The ``SuluSecurityListener`` appends the information on which type of
permission (`view`, `add`, `edit`, `delete`, ...) is required, and
automatically takes care of the permission check and returns a page with a
status code of `403` in case the permissions for the currently logged in user
where not sufficient.

Protecting specific objects
---------------------------

For some parts of your application you might want to protect specific objects.
This section will describe how this is done with the possibilities Sulu offers.

Adding the permission tab to your form
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

First of all you have to add the permission tab to your form to enable the user
to set up the permissions accordingly. The permission tab presents a list of
the available user roles and a few permission icons, which can be activated.

Therefore the `Admin` class you have created for your own business objects
needs some updating. You can add the permission tab as shown below:

.. code-block:: php

    <?php
    
    namespace Sulu\Bundle\ExampleBundle\Admin;
    
    use Sulu\Bundle\AdminBundle\Admin\Admin;
    use Sulu\Bundle\AdminBundle\Admin\View\ToolbarAction;
    use Sulu\Bundle\AdminBundle\Admin\View\ViewBuilderFactoryInterface;
    use Sulu\Bundle\AdminBundle\Admin\View\ViewCollection;
    
    class ExampleAdmin extends Admin
    {
        /**
         * @var ViewBuilderFactoryInterface
         */
        private $viewBuilderFactory;
    
        public function __construct(
            ViewBuilderFactoryInterface $viewBuilderFactory,
        ) {
            $this->viewBuilderFactory = $viewBuilderFactory;
        }
    
        public function configureViews(ViewCollection $viewCollection): void
        {
            // ...
            $viewCollection->add(
                $this->viewBuilderFactory
                    ->createFormViewBuilder('sulu_example.edit_form.permissions', '/permissions')
                    ->setResourceKey('permissions')
                    ->setFormKey('permission_details')
                    ->setRequestParameters(['resourceKey' => 'example'])
                    ->setTabCondition('_permissions.security')
                    ->setTabTitle('sulu_security.permissions')
                    ->addToolbarActions([new ToolbarAction('sulu_admin.save')])
                    ->setParent(static::EDIT_FORM_VIEW)
            );
        }
    }

The important option here is set in the ``setRequestParameters`` call, which
defines for which resource this permission form is used. In order for that to
work the relation between the ``resourceKey`` and the security context and the
security class has to be configured:

.. code-block: yaml

    resources:
        example:
            routes:
                list: 'get_examples'
                detail: 'get_example'
            security_context: 'sulu_admin.example'
            security_class: 'App\\Entity\\Example'

After this addition the permission tab should already be visible in the edit
form.

Configure the controller
~~~~~~~~~~~~~~~~~~~~~~~~

The second part is to implement the `SecuredObjectControllerInterface` in the
Controller handling the specific type of entities:

.. code-block:: php

    <?php

    namespace Acme\Bundle\ExampleBundle\Controller;

    use FOS\RestBundle\Routing\ClassResourceInterface;
    use Sulu\Component\Security\Authorization\AccessControl\SecuredObjectControllerInterface;
    use Sulu\Component\Security\SecuredControllerInterface;
    use Symfony\Component\HttpFoundation\Request;

    class ExampleController
        implements ClassResourceInterface, SecuredControllerInterface, SecuredObjectControllerInterface
    {
        public function cgetAction()
        {
            $listBuilder = $factory->create($this->container->getParameter('sulu.model.example.class'));
            $this->get('sulu_core.doctrine_rest_helper')->initializeListBuilder($listBuilder, $this->getFieldDescriptors());
            
            $listBuilder->setPermissionCheck($this->getUser(), PermissionTypes::VIEW);

            $listResponse = $listBuilder->execute();

            // Do something with $listResponse
        }

        public function postAction()
        {
            // code for your post action
        }

        // ...

        public function getLocale(Request $request)
        {
            return $request->get('locale');
        }

        public function getSecurityContext()
        {
            return 'sulu.acme.example';
        }

        public function getSecuredClass()
        {
            return Example::class;
        }

        public function getSecuredObjectId(Request $request)
        {
            return $request->get('id');
        }
    }

The `SecuredObjectControllerInterface` requires three different methods. The
`getLocale` method is the same as in the `SecuredControllerInterface`, and the
implementation can be shared. The `getSecuredClass` method has to return the
same identifier for the type of object as used in the resources configuration.
Finally the `getSecuredObjectId` receives the request object, and has to return
the id of the object from it.

The rest of the work will be done by the `SuluSecurityListener` in the same way
as for the check of the security contexts.

Note that the `cgetAction` needs some special handling when the `ListBuilder`
is used. The `ListBuilder` contains a `setPermissionCheck` method, which takes
a user and a permission. If you pass these two, you will only receive rows for
which the given user has the given permission granted.
