Securing your application
=========================

Sulu is delivered with two different possiblities to protect parts of your
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

    class AcmeExampleAdmin extends Admin
    {
        // ...

        public function getSecurityContexts()
        {
            return array(
                'Sulu' => array(
                    'Acme' => array(
                        'sulu.acme.example'
                    )
                );
            );
        }

        // ...
    }

This information is defined in the ``getSecurityContexts`` method, which should
return an array. The first level describes the system to which the security
context applies - this would either be Sulu (for stuff in the administration)
or a different context that you have defined manually.

The second level just defines the title for another separation used in the
administration interface. The third and last level defines the name of the
permissions themselves. This name follows a namespacing scheme based on the
previously used names.

.. note::
    
    Since the ``Admin`` class is registered as a bundle, you can make use of
    different services to define the available security contexts. For example
    the SuluContentBundle uses a service to create an own security context for
    all available webspaces in the system.

Protect your controller
~~~~~~~~~~~~~~~~~~~~~~~

After defining a security context, you can use it to easily protect the actions
of one of your controllers. All you have to do is to implement the
``SecuredControllerInterface`` telling the ``SuluSecuriyListener`` which
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

