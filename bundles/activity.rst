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

Listen for an event
-------------------

Behind the scenes, the ActivityBundle uses events for recording activities that happen in the application.
Each recorded activity is created based on an event that was dispatched using the `Symfony event dispatcher`_.
This makes it possible to listen and react to a specific event in a project. For example, the following code
sends an email when a page with a specific template is created in the application:

.. code-block:: php

    <?php

    namespace App\EventSubscriber;

    use Sulu\Bundle\PageBundle\Domain\Event\PageCreatedEvent;
    use Symfony\Component\EventDispatcher\EventSubscriberInterface;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\Mime\Email;

    class SendPageCreatedMailSubscriber implements EventSubscriberInterface
    {
        public function __construct(private MailerInterface $mailer) { }

        public static function getSubscribedEvents()
        {
            return [
                PageCreatedEvent::class => 'sendPageCreatedMail',
            ];
        }

        public function sendPageCreatedMail(PageCreatedEvent $event): void
        {
            if ('product' === $event->getPageDocument()->getStructureType()) {
                return;
            }

            $email = (new Email())
                ->from('from@example.com')
                ->to('to@example.com')
                ->subject('New product page created')
                ->text('Page Title: ' . $event->getPageDocument()->getTitle());

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

    use Sulu\Bundle\ActivityBundle\Domain\Event\DomainEvent;
    use App\Entity\Book;

    class BookCreatedEvent extends DomainEvent
    {
        public function __construct(
            private Book $book
        ) {
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
    use Sulu\Bundle\ActivityBundle\Application\Dispatcher\DomainEventDispatcherInterface;
    use Sulu\Bundle\ActivityBundle\Application\Collector\DomainEventCollectorInterface;

    class BookService
    {
        private EntityManagerInterface $entityManager;

        private DomainEventDispatcherInterface $domainEventDispatcher;

        private DomainEventCollectorInterface $domainEventCollector;

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
Each activity is mapped to a translation key with the format `sulu_activity.description.%resourceKey%.%activityType%``.
For example, the translation key for the activity shown above is ``sulu_activity.description.book.created``:

.. code-block:: json

    {
        "sulu_activity.description.book.created": "{userFullName} has created the Book \"{resourceTitle}\""
    }

The translation text can include placeholders that are replaced with activity specific information. For example,
``{resourceTitle}`` will be replaced with the title of the affected resource. Have a look at the implementation of the
`ActivityController class`_ of the ActivityBundle to find all available placeholders.

Configure permissions for custom activities
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Activities are visible for all users that are allowed to see the activity list in the administration interface
per default. To restrict this, it is possible to return a :doc:`security context<../cookbook/securing-your-application>`
from the ``getResourceSecurityContext`` method.
An activity that returns a security context from the ``getResourceSecurityContext`` method  will only be visible for
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

Extending the Admin View with a Activity Table
------------------------------------------------

To extend the admin view by adding a activities table, follow these steps:

	1.	Inject the `ActivityViewBuilderFactoryInterface`: Inject this interface into your custom Admin class. This will allow you to utilize the necessary methods to create the activities view.
	2.	Create the Activity List View: Use the `createActivityListViewBuilder` method to create the list view for the activities table.

Here’s an example implementation, demonstrating how to add the activities tab to your custom admin view, for further examples take a look at the SnippetAdmin class:

.. code-block:: php

    if ($this->activityViewBuilderFactory->hasActivityListPermission()) {
        $viewCollection->add(
            $this->activityViewBuilderFactory
                ->createActivityListViewBuilder(
                    $insightsResourceTabViewName . '.activity',
                    '/activities',
                    CustomEntity::RESOURCE_KEY
                )
                ->setParent($insightsResourceTabViewName)
        );
    }

The `hasActivityListPermission` method ensures that the current user has permission to view the activities list.
The `createActivityListViewBuilder` method is used to create the view. It takes three parameters:

    - The name of the new view, usually appended with .activity to indicate it is an activity view.
    - The URL path for the activities table.
    - The resource key identifies the type of resource for the activities.

The setParent method sets the parent view to integrate the activities table into the existing admin view.

.. _Symfony event dispatcher: https://symfony.com/doc/current/event_dispatcher.html
.. _DomainEvent class: https://github.com/sulu/sulu/blob/2.6/src/Sulu/Bundle/ActivityBundle/Domain/Event/DomainEvent.php
.. _ActivityController class: https://github.com/sulu/sulu/blob/2.6/src/Sulu/Bundle/ActivityBundle/UserInterface/Controller/ActivityController.php#L377-L401
.. _Symfony translations: https://symfony.com/doc/current/translation.html
