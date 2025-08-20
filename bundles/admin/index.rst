AdminBundle
============

The AdminBundle provides all functionality for the Sulu Admin UI. It allows
to extend the Admin UI to integrate custom functionality or modify existing
views with new functionality.

The Admin Class
---------------

The Admin class is the main entry point for the Admin UI. It is responsible
to configure Navigation Items, Views, Security and other parts of the Admin UI.

It is best practice to create one Admin class per resource type, e.g. one for an
``Event`` resource.

.. code-block:: php

    <?php

    namespace App\Admin;

    use Sulu\Bundle\AdminBundle\Admin\Admin;
    use Sulu\Bundle\AdminBundle\Admin\Navigation\NavigationItemCollection;
    use Sulu\Bundle\AdminBundle\Admin\View\ViewBuilderFactoryInterface;
    use Sulu\Bundle\AdminBundle\Admin\View\ViewCollection;

    class EventAdmin extends Admin
    {
        public function __construct(private ViewBuilderFactoryInterface $viewBuilderFactory)
        {
        }

        public function configureNavigationItems(NavigationItemCollection $navigationItemCollection): void
        {
            // add navigation items
        }

        public function configureViews(ViewCollection $viewCollection): void
        {
            // add views
        }

        public function getSecurityContexts(): array
        {
            return [];
        }
    }

Navigation Items
----------------

Navigation Items are the items in the left sidebar of the Admin UI. They are configurable
and are linking to different views.

.. code-block:: php

    <?php

    namespace App\Admin;

    use Sulu\Bundle\AdminBundle\Admin\Admin;
    use Sulu\Bundle\AdminBundle\Admin\Navigation\NavigationItem;
    use Sulu\Bundle\AdminBundle\Admin\Navigation\NavigationItemCollection;

    class EventAdmin extends Admin
    {
        public const LIST_VIEW = 'app_event.list';

        public function configureNavigationItems(NavigationItemCollection $navigationItemCollection): void
        {
            $item = new NavigationItem('app.events'); // translation key inside the admin translation domain
            $item->setPosition(70);
            $item->setIcon('su-clock'); // icon from Sulu icon font or font awesome seehttps://jsdocs.sulu.io/2.5/#icon
            $item->setView(static::LIST_VIEW);

            $navigationItemCollection->add($item);
        }

        // ...
    }

Every item can have a title, an icon, a position and a view. The view is the key which best practices is
to reference by a constants which you also use in the View Builder tackled later in this documentations.

Security Checker
----------------

If you have different user roles you want to make sure only specific users see specific navigation items or view.
Via injecting the securityChecker service and providing new security context it is possible to check if a user
has permissions for a specific security context.

.. code-block:: php

    <?php

    namespace App\Admin;

    use Sulu\Bundle\AdminBundle\Admin\Admin;
    use Sulu\Bundle\AdminBundle\Admin\Navigation\NavigationItemCollection;
    use Sulu\Bundle\AdminBundle\Admin\View\ViewCollection;
    use Sulu\Component\Security\Authorization\PermissionTypes;

    class EventAdmin extends Admin
    {
        public const SECURITY_CONTEXT = 'sulu.event.events';

        public function __construct(
            private SecurityCheckerInterface $securityChecker,
        ) {
        }

        public function configureNavigationItems(NavigationItemCollection $navigationItemCollection): void
        {
            if ($this->securityChecker->hasPermission(static::SECURITY_CONTEXT, PermissionTypes::EDIT)) {
                // add navigation items
            }
        }

        public function configureViews(ViewCollection $viewCollection): void
        {
            if ($this->securityChecker->hasPermission(static::SECURITY_CONTEXT, PermissionTypes::EDIT)) {
                // add views
            }
        }

        public function getSecurityContexts()
        {
            return [
                self::SULU_ADMIN_SECURITY_SYSTEM => [
                    'Event' => [
                        self::SECURITY_CONTEXT => [
                            PermissionTypes::VIEW,
                            PermissionTypes::ADD,
                            PermissionTypes::EDIT,
                            PermissionTypes::DELETE,
                        ],
                    ],
                ],
            ];
        }
    }

Views
-----

Views are the main content of the Admin UI. They are configurable and required to extend the Admin UI
with new tabs and navigation items.

List View Builder
~~~~~~~~~~~~~~~~~

ToDo

Form View Builder
~~~~~~~~~~~~~~~~~

ToDo
