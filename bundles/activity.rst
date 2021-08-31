ActivityBundle
==============

The ActivityBundle implements is responsible for recording activities that happen in the application and allows
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
        /**
         * @var MailerInterface $mailer
         */
        private $mailer;

        public function __construct(MailerInterface $mailer) {
            $this->mailer = $mailer;
        }

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
        /**
         * @var Book
         */
        private $book;

        /**
         * @param mixed[] $payload
         */
        public function __construct(
            Book $book
        ) {
            parent::__construct();

            $this->book = $book;
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
        /**
         * @var EntityManagerInterface
         */
        private $entityManager;

        /**
         * @var DomainEventDispatcherInterface
         */
        private $domainEventDispatcher;

        /**
         * @var DomainEventCollectorInterface
         */
        private $domainEventCollector;

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

The descriptions in the Admin list view are generated via the `Symfony translation`_.The translation key is composed of the following combination. "sulu_activity.description.resourceKey.type" as an example. "sulu_activity.description.book.created"
In order to capture the logged-in user and the title of the resource, you can create the translation as follows.

.. code-block:: json

    {
        "sulu_activity.description.book.created": "{userFullName} has created the Book \"{resourceTitle}\""
    }


.. _Symfony event dispatcher: https://symfony.com/doc/current/event_dispatcher.html
.. _DomainEvent class: https://github.com/sulu/sulu/blob/2.x/src/Sulu/Bundle/ActivityBundle/Domain/Event/DomainEvent.php
.. _Symfony translation: https://symfony.com/doc/current/translation.html
