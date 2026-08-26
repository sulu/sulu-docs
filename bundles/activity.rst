ActivityBundle
==============

The ActivityBundle is responsible for recording activities that happen in the application and allows
developer to listen for changes and other events that happen in the system.

Configuration
-------------

The ActivityBundle allows for the following configuration:

.. code-block:: yaml

    # config/packages/sulu_activity.yaml
    sulu_activity:
        storage:
            adapter: 'doctrine' # can be set to null to not store activities
            persist_payload: false # include payload of event in stored activity
        objects:
            activity:
                model: Sulu\Bundle\ActivityBundle\Domain\Model\Activity # override to use a custom activity entity

Listen for an event
-------------------

Behind the scenes, the ActivityBundle uses events for recording activities that happen in the application.
Each recorded activity is created based on an event that was dispatched using the `Symfony event dispatcher`_.
This makes it possible to listen and react to a specific event in a project. For example, the following code
sends an email when a page with a specific template is created in the application:

.. code-block:: php

    <?php

    namespace App\EventSubscriber;

    use Sulu\Page\Domain\Event\PageCreatedEvent;
    use Symfony\Component\EventDispatcher\EventSubscriberInterface;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\Mime\Email;

    class SendPageCreatedMailSubscriber implements EventSubscriberInterface
    {
        public function __construct(private MailerInterface $mailer) {}

        public static function getSubscribedEvents(): array
        {
            return [
                PageCreatedEvent::class => 'sendPageCreatedMail',
            ];
        }

        public function sendPageCreatedMail(PageCreatedEvent $event): void
        {
            $dimensionContent = $event->getPage()->getDimensionContents()
                ->findFirst(fn($key, $dc) => $dc->getLocale() === $event->getResourceLocale());

            if ('product' !== $dimensionContent?->getTemplateKey()) {
                return;
            }

            $email = (new Email())
                ->from('from@example.com')
                ->to('to@example.com')
                ->subject('New product page created')
                ->text('Page Title: ' . $event->getResourceTitle());

            $this->mailer->send($email);
        }
    }

Store a custom activity
-----------------------

To store a custom activity in your project, you need to implement a respective event and dispatch it using the
services provided by the ActivityBundle. Your event needs to extend the ``DomainEvent`` class of the activity
bundle and could look like this:

.. code-block:: php

    <?php

    namespace App\Event;

    use App\Entity\Book;
    use Sulu\Bundle\ActivityBundle\Domain\Event\DomainEvent;

    class BookCreatedEvent extends DomainEvent
    {
        public function __construct(private Book $book)
        {
            parent::__construct();
        }

        public function getEventType(): string
        {
            return 'created';
        }

        public function getResourceKey(): string
        {
            return Book::RESOURCE_KEY;
        }

        public function getResourceId(): string
        {
            return (string) $this->book->getId();
        }

        public function getResourceTitle(): ?string
        {
            return $this->book->getTitle();
        }
    }

Have a look at the `DomainEvent class`_ of the ActivityBundle to see all methods that can be overwritten by your
event. After implementing your event, you can dispatch it in your code using one of the two options shown below:

.. code-block:: php

    <?php

    namespace App\Service;

    use App\Event\BookCreatedEvent;
    use Doctrine\ORM\EntityManagerInterface;
    use Sulu\Bundle\ActivityBundle\Application\Collector\DomainEventCollectorInterface;
    use Sulu\Bundle\ActivityBundle\Application\Dispatcher\DomainEventDispatcherInterface;

    class BookService
    {
        public function __construct(
            private EntityManagerInterface $entityManager,
            private DomainEventDispatcherInterface $domainEventDispatcher,
            private DomainEventCollectorInterface $domainEventCollector,
        ) {}

        public function createBook(array $data): Book
        {
            // ...

            // first option: use the DomainEventDispatcher service to immediately record the activity
            $this->domainEventDispatcher->dispatch(new BookCreatedEvent($book));

            // second option: use the DomainEventCollector service to automatically record the activity after the
            // EntityManager::flush() method was called
            $this->domainEventCollector->collect(new BookCreatedEvent($book));
            $this->entityManager->flush();
        }
    }

Configure description text for a custom activity
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Activity descriptions that are displayed in the administration interface are generated using
`Symfony translations`_.
Each activity is mapped to a translation key with the format ``sulu_activity.description.%resourceKey%.%activityType%``.
For example, the translation key for the activity shown above is ``sulu_activity.description.book.created``:

.. code-block:: json

    {
        "sulu_activity.description.book.created": "{userFullName} has created the Book \"{resourceTitle}\""
    }

The translation text can include the following placeholders that are replaced with activity-specific information:

- ``{userFullName}`` — full name of the user who triggered the activity (shown in bold)
- ``{resourceTitle}`` — title of the affected resource
- ``{resourceKey}`` — resource key of the affected resource
- ``{resourceId}`` — ID of the affected resource
- ``{resourceLocale}`` — locale of the affected resource
- ``{resourceWebspaceKey}`` — webspace key of the affected resource

Additional placeholders can be provided by overriding the ``getEventContext()`` method in your event class.
Each key returned from ``getEventContext()`` becomes available as ``{context_key}`` in the translation string:

.. code-block:: php

    public function getEventContext(): array
    {
        return [
            'bookIsbn' => $this->book->getIsbn(),
        ];
    }

This makes ``{context_bookIsbn}`` available in the translation string:

.. code-block:: json

    {
        "sulu_activity.description.book.created": "{userFullName} has created the Book \"{resourceTitle}\" (ISBN: {context_bookIsbn})"
    }

Configure permissions for custom activities
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Activities are visible for all users that are allowed to see the activity list in the administration interface
per default. To restrict this, it is possible to return a :doc:`security context<../cookbook/securing-your-application>`
from the ``getResourceSecurityContext`` method.
An activity that returns a security context from the ``getResourceSecurityContext`` method will only be visible for
users with a ``view`` permission for the context:

.. code-block:: php

    <?php

    namespace App\Event;

    use Sulu\Bundle\ActivityBundle\Domain\Event\DomainEvent;

    class BookCreatedEvent extends DomainEvent
    {
        public function getResourceSecurityContext(): ?string
        {
            return BookAdmin::SECURITY_CONTEXT;
        }
    }

Extending the Admin View with an Activity Table
------------------------------------------------

To extend the admin view by adding an activities table, follow these steps:

1. Inject ``ActivityViewBuilderFactoryInterface`` into your custom Admin class.
2. Use the ``createActivityListViewBuilder`` method to create the list view for the activities table.

Here is an example implementation demonstrating how to add the activities tab to your custom admin view.
For further examples, take a look at the ``SnippetAdmin`` class:

.. code-block:: php

    if ($this->activityViewBuilderFactory->hasActivityListPermission()) {
        $viewCollection->add(
            $this->activityViewBuilderFactory
                ->createActivityListViewBuilder(
                    $insightsResourceTabViewName . '.activity',
                    '/activities',
                    CustomEntity::RESOURCE_KEY,
                    'id' // optional: router attribute used as the resourceId request parameter (default: 'id')
                )
                ->setParent($insightsResourceTabViewName)
        );
    }

``createActivityListViewBuilder`` accepts four parameters:

- ``$name`` — name of the new view, usually appended with ``.activity``
- ``$path`` — URL path for the activities table
- ``$resourceKey`` — resource key identifying the type of resource for the activities
- ``$resourceIdRouterAttribute`` — router attribute used as the ``resourceId`` request parameter (default: ``'id'``)

The ``hasActivityListPermission`` method ensures that the current user has permission to view the activities list.
The ``setParent`` method integrates the activities table into the existing admin view.

.. _Symfony event dispatcher: https://symfony.com/doc/current/event_dispatcher.html
.. _DomainEvent class: https://github.com/sulu/sulu/blob/3.0/src/Sulu/Bundle/ActivityBundle/Domain/Event/DomainEvent.php
.. _ActivityController class: https://github.com/sulu/sulu/blob/3.0/src/Sulu/Bundle/ActivityBundle/UserInterface/Controller/ActivityController.php#L371-L385
.. _Symfony translations: https://symfony.com/doc/current/translation.html
