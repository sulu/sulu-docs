Security System
===============

Sulu Security Bundle provides the possibility to work with different security systems.
This means a user can only login into a configured symfony firewall when the user has
atleast one role in the current matching security system. A role is always related to
on security system.

By default there is one Security system the ``Sulu`` which is used in the admin.

Webspace Security System
------------------------

Every webspace can also define a security system, this is required when a website requires
some kind of a login:

.. code:: xml

    <security>
        <system>Website</system>
    </security>

To use object security on pages, media or custom entities you need to enable ``permission-check``:

.. code-block:: xml

    <security permission-check="true">
        <system>example</system>
    </security>

For this it is important to activate :doc:`../../cookbook/user-context-caching`.

Custom Security System
----------------------

To add a custom security system. Beside the admin security system on webspace
security system can be done via a custom Admin class. This is common for
intranet or extranets which are not related to a webspace:

.. code-block:: php

    namespace App\Sulu\Admin;

    use Sulu\Bundle\AdminBundle\Admin\Admin;

    class ExtranetAdmin extends Admin
    {
        public const SYSTEM = 'Extranet';

        public function getSecurityContexts()
        {
            return [self::SYSTEM => []];
        }
    }

That the sulu user provider knows about which system is currently active a
request listener need to be registered which sets the current system for
the system store service:

.. code-block:: php

    namespace App\Sulu\Security;

    use App\Sulu\Admin\ExtranetAdmin;
    use Sulu\Bundle\SecurityBundle\System\SystemStoreInterface;
    use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
    use Symfony\Component\EventDispatcher\EventSubscriberInterface;
    use Symfony\Component\HttpKernel\Event\RequestEvent;
    use Symfony\Component\HttpKernel\KernelEvents;
    use Symfony\Component\Security\Http\FirewallMapInterface;

    class SecuritySystemSubscriber implements EventSubscriberInterface
    {
        private FirewallMap $map;

        private SystemStoreInterface $systemStore;

        public function __construct(
            SystemStoreInterface $systemStore,
            FirewallMapInterface $map,
        ) {
            $this->systemStore = $systemStore;

            if (!$map instanceof FirewallMap) {
                throw new \LogicException(\sprintf('Expected "%s" but got "%s".', FirewallMap::class, \get_class($map)));
            }

            $this->map = $map;
        }

        public static function getSubscribedEvents(): array
        {
            return [
                KernelEvents::REQUEST => [
                    // need to be after @see \Sulu\Bundle\SecurityBundle\EventListener\SystemListener::getSubscribedEvents
                    // need to be before @see \Symfony\Bundle\SecurityBundle\EventListener\FirewallListener::getSubscribedEvents
                    ['processSecuritySystem', 9],
                ],
            ];
        }

        public function processSecuritySystem(RequestEvent $event): void
        {
            if (!$event->isMainRequest()) {
                return;
            }

            $config = $this->map->getFirewallConfig($event->getRequest());
            if (!$config) {
                return;
            }

            if ('extranet' === $config->getName()) {
                $this->systemStore->setSystem(ExtranetAdmin::SYSTEM);
            }
        }
    }

System Store
------------

The system store is the service which provides ``sulu_security.system_store``
the current security system. This is example used by the UserProvider to
provide only users which are in the current system. It is also used for
smart content providers to return only objects which the current user has
access for.
