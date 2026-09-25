NotifierBundle
==============

The ``SuluNotifierBundle`` sends a chat notification when a configured event is dispatched, for example
when a page is modified or a workflow transition is applied. It builds on the `Symfony Notifier`_, so
every Symfony chat transport can be used. Available since Sulu 3.1.

Installation
------------

The bundle is optional and requires ``symfony/notifier`` and at least one chat transport:

.. code-block:: bash

    composer require symfony/notifier symfony/slack-notifier

Register the bundle in ``config/bundles.php``:

.. code-block:: php

    Sulu\Notifier\Infrastructure\Symfony\HttpKernel\SuluNotifierBundle::class => ['all' => true],

Configuration
-------------

Configure the chat transport in the Symfony Notifier configuration:

.. code-block:: yaml

    # config/packages/notifier.yaml
    framework:
        notifier:
            chatter_transports:
                slack: '%env(SLACK_DSN)%'

Then map each channel to the events that should be sent to it. A channel is ``chat/<transport name>``:

.. code-block:: yaml

    # config/packages/sulu_notifier.yaml
    sulu_notifier:
        channels:
            'chat/slack':
                - Sulu\Page\Domain\Event\PageModifiedEvent
                - Sulu\Page\Domain\Event\PageWorkflowTransitionAppliedEvent
                - Sulu\Article\Domain\Event\ArticleWorkflowTransitionAppliedEvent

Events that are not listed are not sent.

Message format
--------------

For every event that extends the ``DomainEvent`` of the :doc:`activity`, the notification contains:

- the subject, translated from ``sulu_notifier.subject.<resourceKey>.<eventType>``, e.g. "Page modified"
- the description of the activity log, translated from ``sulu_activity.description.<resourceKey>.<eventType>``
- a link to the resource in the administration interface, when the resource has a ``detail`` view
- the webspace and the locale of the resource

On a transport named ``slack`` the notification is sent as `Slack blocks`_: a header with the subject,
the description, a context line with webspace and locale and an "Open in Sulu" button. Every other chat
transport receives the subject, the description and the link as plain text.

.. note::

    The Slack layout is chosen by the transport name. When the Slack transport is configured under
    another name, the message is sent as plain text.

Other events fall back to a notification that contains only the class name of the event.

Customize the message
---------------------

Change the texts
^^^^^^^^^^^^^^^^

Subject, description and button label are translations in the ``admin`` domain. Override them in the
translation files of the project, e.g. ``translations/admin.en.json``:

.. code-block:: json

    {
        "sulu_notifier.subject.pages.modified": "Page updated",
        "sulu_activity.description.pages.modified": "{userFullName} updated \"{resourceTitle}\" ({resourceLocale})",
        "sulu_notifier.open_link": "Show page"
    }

The description translation is shared with the activity log in the administration interface.

Build your own notification
^^^^^^^^^^^^^^^^^^^^^^^^^^^

To change the content or the layout of the message, create a service that implements the
`EventNotificationFactoryInterface`_. The first factory that supports an event creates the notification.
With autoconfiguration the service is registered automatically and runs before the factories of Sulu.

The factory can return the ``EventNotification`` of the bundle, which provides the Slack and plain text
rendering described above:

.. code-block:: php

    <?php

    namespace App\Notifier;

    use Sulu\Notifier\Application\Factory\EventNotificationFactoryInterface;
    use Sulu\Notifier\Infrastructure\Symfony\Notifier\EventNotification;
    use Sulu\Page\Domain\Event\PageWorkflowTransitionAppliedEvent;
    use Symfony\Component\Notifier\Notification\Notification;

    class PageWorkflowNotificationFactory implements EventNotificationFactoryInterface
    {
        public function supports(object $event): bool
        {
            return $event instanceof PageWorkflowTransitionAppliedEvent;
        }

        /**
         * @param list<string> $channels
         */
        public function create(object $event, array $channels): Notification
        {
            \assert($event instanceof PageWorkflowTransitionAppliedEvent);

            return new EventNotification(
                subject: 'Review requested',
                description: \sprintf('"%s" needs a review', $event->getResourceTitle()),
                link: 'https://example.org/review/' . $event->getResourceId(),
                linkLabel: 'Review',
                context: \array_values(\array_filter([$event->getResourceWebspaceKey(), $event->getResourceLocale()])),
                channels: $channels,
            );
        }
    }

Values inserted into ``EventNotification`` are escaped for Slack, so a resource title cannot add
mentions or links to the message.

For a different layout, e.g. Discord embeds or Microsoft Teams cards, return your own ``Notification``
that implements the ``ChatNotificationInterface`` of Symfony and build the chat message per transport, as
described in the `Symfony documentation`_. The deep link to a resource can be generated with the
``Sulu\Bundle\AdminBundle\Admin\View\ResourceViewUrlGeneratorInterface`` service, which only exists in
the admin context:

.. code-block:: php

    $link = $this->resourceViewUrlGenerator->generate(
        $event->getResourceKey(),
        'detail',
        \array_filter([
            'id' => $event->getResourceId(),
            'webspace' => $event->getResourceWebspaceKey(),
            'locale' => $event->getResourceLocale(),
        ]),
        UrlGeneratorInterface::ABSOLUTE_URL,
    );

.. _Symfony Notifier: https://symfony.com/doc/current/notifier.html
.. _Slack blocks: https://api.slack.com/block-kit
.. _EventNotificationFactoryInterface: https://github.com/sulu/sulu/blob/3.1/packages/notifier/src/Application/Factory/EventNotificationFactoryInterface.php
.. _Symfony documentation: https://symfony.com/doc/current/notifier.html#customize-notification-messages
