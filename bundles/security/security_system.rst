Security System
===============

The SecurityBundle supports to work with different security systems. Each role is assigned
to a security system.
When a request happens, the request is assigned to a security system and the symfony firewall allows a user to login only if the user has at least one role in the assigned security system.

By default, there is a single security system called ``Sulu`` which is used for the administration interface.

Webspace Security System
------------------------

A webspace can define a security system in its configuration. The security system will be
assigned to the request when a user visits a page of the webspace.

If your webspace requires some kind of login, you should define a security system in its configuration:

.. code:: xml

    <security>
        <system>Website</system>
    </security>

If you want to restrict pages, media entities or custom entities to logged in users with a specific role,
you need to enable ``permission-check`` in the webspace configuration:

.. code-block:: xml

    <security permission-check="true">
        <system>example</system>
    </security>

To prevent caching problems with restricted entities, it is important to activate :doc:`../../cookbook/user-context-caching`.

Custom Security System
----------------------

You can register a custom security system in a ``Admin`` class. This is useful for sections
like an intranet or an extranet which are not associated to a specific webspace:

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

Additionally, you need to register a request listener that assigns the custom security context to the request.
To do this, you access the ``SystemStore`` service in your listener:

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
        public function __construct(
            private SystemStoreInterface $systemStore,
            private FirewallMapInterface $map,
        ) {
            if (!$map instanceof FirewallMap) {
                throw new \LogicException(\sprintf('Expected "%s" but got "%s".', FirewallMap::class, \get_class($map)));
            }
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

The ``SystemStore`` service is used by the ``UserProvider`` to access the security system of the current request. It is registered with the service id ``sulu_security.system_store``.
