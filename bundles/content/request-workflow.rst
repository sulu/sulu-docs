Request Workflow
================

Available since Sulu 3.1. A request workflow adds a review step before publishing. An editor requests the publishing,
a reviewer approves or rejects it and the content is published afterwards. It works for pages, articles, snippets and
every other content that implements ``WorkflowInterface``. Users with the ``live`` permission can still publish
directly. Without a configured workflow, publishing stays direct.

The workflow only applies to changes made in the admin. Commands, fixtures and message consumers run without a user and
skip the permissions and the pre-validators. In the admin context (``bin/console``, ``bin/adminconsole``) the lock still
holds, so their writes to content with an open request fail, too.

.. note::

    A custom resource needs a ``security_context`` under ``sulu_admin.resources`` once a workflow covers it. Without it,
    publishing answers ``500``. Its admin class also has to list ``PermissionTypes::LIVE`` and
    ``PermissionTypes::REVIEW`` in ``getSecurityContexts()``, otherwise no role can be granted publishing or reviewing.
    See :doc:`../../cookbook/securing-your-application`.

Configuration
-------------

Configure workflows by name in the ``sulu_content`` section:

.. code-block:: yaml

    # config/packages/sulu_content.yaml
    sulu_content:
        request_workflows:
            default:
                resources: ['pages', 'articles']
                required_user_approvals: 1
            strict:
                required_user_approvals: 2
                pre_validators:
                    seo_required:
                        fields: ['title', 'description']
                validators:
                    broken_links:
                        required: true

.. list-table::
    :header-rows: 1

    * - Key
      - Description
    * - ``resources``
      - Resource keys the ``default`` workflow covers for templates without a workflow tag. Empty means none, a template
        tagged ``workflow="default"`` still uses it. Only the ``default`` workflow accepts it, any other workflow with
        ``resources`` is refused.
    * - ``required_user_approvals``
      - How many users have to approve the request. Defaults to ``1``. ``0`` is only accepted together with a
        validator that has ``required: true``.
    * - ``pre_validators``
      - Synchronous checks by key. The value is passed to the pre-validator as its config.
    * - ``validators``
      - Automated checks that run after the request was created, keyed by their key. The config key ``required`` makes
        the request wait for the check (default ``false``).

The name ``none`` is reserved and cannot be used for a workflow.

Sulu ships the pre-validators ``seo_required`` and ``excerpt_required`` and no validators. ``broken_links`` above is the
validator written in :doc:`../../cookbook/request-workflow-validator`.

.. note::

    The container build fails for a validator or pre-validator key that no service registers, for a key that several
    services register, for a template tag that names a workflow that is not configured and for
    ``required_user_approvals: 0`` without a required validator.

Choosing the Workflow of a Template
-----------------------------------

Content without a tag uses the ``default`` workflow if it is configured and its ``resources`` cover the resource key.
A template selects another workflow with the ``sulu_content.request_workflow`` tag:

.. code-block:: xml

    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">

        <key>article_review</key>

        <view>articles/article_review</view>
        <controller>Sulu\Content\UserInterface\Controller\Website\ContentController::indexAction</controller>

        <tag name="sulu_content.request_workflow" workflow="strict"/>

        <!-- ... -->
    </template>

The workflow ``none`` opts a template out of review, also when the ``default`` workflow would cover it. The content of
this template publishes directly:

.. code-block:: xml

    <tag name="sulu_content.request_workflow" workflow="none"/>

Flow
----

#. An editor chooses **Save and request for publish**. This creates a request and starts the automated checks.
#. A user with the ``review`` permission approves the request or rejects it with a comment. Nobody can review their own
   request.
#. The request is approved when ``required_user_approvals`` users approved it and every ``required`` validator passed.
   A rejection is recorded with its comment, but the request stays open.
#. A user with the ``live`` permission, or with the ``edit`` permission and an approved request, publishes the content.
   Publishing closes the request.
#. A user with the ``edit`` permission can cancel an open request. This closes it and unlocks the content. An editor has
   to cancel to change the content after a rejection.

The ``reject`` and ``cancel_review`` transitions of the content API (and their ``_draft`` variants) take the content out
of the review and close the request. ``reject`` needs the ``review`` permission.

Permissions
-----------

.. list-table::
    :header-rows: 1

    * - Action
      - Permission
    * - Request the publishing
      - ``edit``
    * - Approve or reject a request
      - ``review``
    * - Cancel a request or retry a check
      - ``edit``
    * - Publish
      - ``live``, or ``edit`` with an approved request

Without a request workflow, publishing pages, articles and snippets needs ``live``, as in Sulu 3.0. ``live`` does not
include ``review``. Grant it to a role in the **User roles** list under **Settings**.
See :doc:`../security/index` for how permissions work.

Locking
-------

Content with an open request cannot be changed. A write answers ``409`` with the translation key
``sulu_content.content_in_review`` until the request is published, rejected or cancelled.

Checks
------

A *pre-validator* runs synchronously inside the transitions ``request_for_review``, ``request_for_review_draft`` and
``publish``. A failed one blocks the transition. The response is ``422`` with code ``1108`` and the results in
``preValidationResults``, and nothing is written. Sulu provides two pre-validators. Both need a non-empty ``fields``
list and require each listed text field to be non-empty.

.. list-table::
    :header-rows: 1

    * - Key
      - Fields that can be listed
    * - ``seo_required``
      - ``title``, ``description``, ``keywords``, ``canonicalUrl`` of the SEO data
    * - ``excerpt_required``
      - ``title``, ``more``, ``description`` of the excerpt data

A *validator* runs when the request was created. It never counts as a user approval. A ``required`` validator holds the
request back until it passes, any other one is informational. A user with the ``edit`` permission can run a check
again from the review overlay.

:doc:`../../cookbook/request-workflow-validator` describes how to write both.

.. note::

    Validators run as the Symfony Messenger message
    ``Sulu\Content\Application\Message\ValidateWorkflowTransitionRequestMessage``. Without a routing for it, the message
    is handled synchronously. To run the checks in a worker, route the message to a transport as described in the
    `Symfony Messenger documentation`_ and consume it with ``bin/adminconsole messenger:consume``, because the handlers
    are only registered in the admin context. A required check keeps the request pending until a worker has answered.

Admin
-----

The default toolbar of the form shows **Save as draft**, **Save and request for publish**, **Save and publish** and
**Publish**, depending on the permissions of the user. **Save and request for publish** only shows for templates a
workflow covers. While a request is open, a banner above the form shows its state with a **Cancel request for publish**
action, and the toolbar shows **Review**. **Review** opens an overlay where reviewers approve or reject and see the
state of every check. The **Requests for Publishing** tab under **Insights**
lists the requests of the content in the current locale.

A custom form view with its own toolbar gets the same actions from the content view builder factory:

.. code-block:: php

    $toolbarActions = $this->contentViewBuilderFactory->getWorkflowTransitionRequestToolbarActions(Article::class);

    // $toolbarActions['save'] and $toolbarActions['approval']

The review overlay needs the admin API routes of the ContentBundle:

.. code-block:: yaml

    # config/routes/sulu_admin.yaml
    sulu_content_api:
        resource: "@SuluContentBundle/config/routing_admin_api.yaml"
        prefix: /admin/api

Extending the Request
---------------------

A bundle can add its own fields and its own footer actions to the request for publishing. It can also react when a
request ends. The core does not need to know about it.

.. note::

    The events and the action interface below are marked ``@experimental``. They may change in future releases.

Request Form
~~~~~~~~~~~~

**Save and request for publish** opens an overlay with the form ``workflow_transition_request_details`` before it saves.
The form has one field, ``message``, a text area for the reviewer. The review overlay shows the message to the reviewer.
The overlay only opens when the form has at least one field.

Add fields by extending the form. Create a form with the same key, as described in
:doc:`../../reference/property-types/block`:

.. code-block:: xml

    <?xml version="1.0" ?>
    <!-- config/forms/workflow_transition_request_details.xml -->
    <form xmlns="http://schemas.sulu.io/template/template"
          xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
          xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/form-1.0.xsd"
    >
        <key>workflow_transition_request_details</key>

        <properties>
            <property name="publishAt" type="datetime"/>
        </properties>
    </form>

The admin sends the form data in the body of the save, under the key ``workflowTransitionRequestData``. The page,
article and snippet controllers remove the key from the content data and pass it on. The request stores it as JSON in
the new nullable column ``data`` of ``ct_workflow_transition_requests``. Run ``bin/console doctrine:migrations:migrate``
after the update. The API returns the stored values as ``data``.

Reading the Data
~~~~~~~~~~~~~~~~

Two events tell that a request ended:

.. list-table::
    :header-rows: 1

    * - Event
      - Dispatched when
    * - ``WorkflowTransitionRequestSettledEvent``
      - During the publish transition of an approved request, before the marking is written and flushed. The publish can
        still fail after the event.
    * - ``WorkflowTransitionRequestCancelledEvent``
      - During a cancel or reject transition, before the marking is written and flushed. Not when the request is deleted
        with its content or locale.

Both live in ``Sulu\Content\Application\WorkflowTransitionRequest\Event`` and offer ``getResourceKey()``,
``getResourceId()``, ``getLocale()`` and ``getData()``. The settled event comes from the publish itself. Use it for
what follows the publish. Schedule things in a footer action, as shown below. The example drops the schedule of the
action, because the content is published now:

.. code-block:: php

    <?php

    declare(strict_types=1);

    namespace App\EventListener;

    use Sulu\Content\Application\WorkflowTransitionRequest\Event\WorkflowTransitionRequestSettledEvent;
    use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

    #[AsEventListener]
    class DropScheduleListener
    {
        public function __construct(
            private readonly PublishScheduler $publishScheduler,
        ) {
        }

        public function __invoke(WorkflowTransitionRequestSettledEvent $event): void
        {
            $this->publishScheduler->cancel(
                $event->getResourceKey(),
                $event->getResourceId(),
                $event->getLocale(),
            );
        }
    }

Use the cancelled event to undo what the bundle set up for the request.

.. warning::

    ``ContentWorkflowInterface::apply()`` has a fourth argument, ``array $workflowTransitionRequestData = []``.
    Classes that implement the interface have to add it. Callers can leave it out. See ``UPGRADE-3.x.md`` of Sulu.

Footer Actions
~~~~~~~~~~~~~~

An action adds a button to the footer of the review overlay, next to the actions of the core. Implement
``WorkflowTransitionRequestActionInterface``. The interface is autoconfigured with the tag
``sulu_content.workflow_transition_request_action``, so a class that implements it needs nothing else:

.. code-block:: php

    <?php

    declare(strict_types=1);

    namespace App\RequestWorkflow;

    use Sulu\Component\Security\Authorization\PermissionTypes;
    use Sulu\Content\Application\WorkflowTransitionRequest\Action\WorkflowTransitionRequestActionInterface;
    use Sulu\Content\Domain\Model\WorkflowTransitionRequest\WorkflowTransitionRequest;

    class PublishAtAction implements WorkflowTransitionRequestActionInterface
    {
        public function __construct(
            private readonly PublishScheduler $publishScheduler,
        ) {
        }

        public static function getKey(): string
        {
            return 'publish_at';
        }

        public function getLabel(): string
        {
            return 'app.publish_on';
        }

        public function getLabelParameters(WorkflowTransitionRequest $request): array
        {
            return ['date' => $request->getData()['publishAt'] ?? ''];
        }

        public function getPermission(): string
        {
            return PermissionTypes::LIVE;
        }

        public function getStatusMessage(WorkflowTransitionRequest $request): ?array
        {
            return null;
        }

        public function replacesPublish(WorkflowTransitionRequest $request): bool
        {
            return false;
        }

        public function isAvailable(WorkflowTransitionRequest $request): bool
        {
            return isset($request->getData()['publishAt']);
        }

        public function execute(WorkflowTransitionRequest $request): void
        {
            $this->publishScheduler->schedule(
                $request->getResourceKey(),
                $request->getResourceId(),
                $request->getLocale(),
                new \DateTimeImmutable($request->getData()['publishAt']),
            );
        }
    }

The action schedules the publish. ``PublishScheduler`` stands for your own service, with a ``schedule()`` and a
``cancel()`` method. Add an entry for the label to the admin translations, for example
``"app.publish_on": "Publish on {date}"`` in ``translations/admin.en.json``.

.. list-table::
    :header-rows: 1

    * - Method
      - Purpose
    * - ``getKey()``
      - Unique and static. It names the action in the API. The keys ``approve``, ``reject``, ``retry``, ``cancel`` and
        ``publish`` belong to the core. Using one throws a ``\RuntimeException`` when the container is built.
    * - ``getLabel()``
      - Translation key of the button label.
    * - ``getLabelParameters()``
      - Values for the placeholders of the label, for example ``{date}``. Return an empty array when it has none.
    * - ``getStatusMessage()``
      - Replaces the message of the banner of the form while the request is open, for example to tell that a publish is
        scheduled. Return ``['message' => 'app.scheduled', 'parameters' => ['date' => '...']]`` with a translation key,
        or ``null`` to keep the message of the core. The first action that answers wins. It is asked whether the action
        is available or not.
    * - ``replacesPublish()``
      - Whether the content may only be published through this action while the request is open. The API then reports
        ``permissions.publish`` as ``false``, so the admin hides the plain publish buttons. The content workflow also
        refuses a publish by a user. A publish without a user, like the one of a scheduled task, still passes.
    * - ``getPermission()``
      - Permission the user needs on the content, to see the button and to take the action.
    * - ``isAvailable()``
      - Whether the action fits the request. Only open requests are asked.
    * - ``execute()``
      - Runs inside the HTTP request and is flushed with it. Throw an exception to refuse.

The API lists the actions that fit a request and that the user may take as ``actions``, each with its ``key`` and
``label``. A closed request has none. A click runs:

.. code-block:: text

    POST /admin/api/workflow-transition-requests/{id}?action=<key>

The answer is ``400`` for an unknown key. It is ``409`` for a closed request and for an action that is not available for
the request.

Entries in the Save Dropdown
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

A bundle can add entries to the save dropdown of a content form, behind the entries of the core. Implement
``SaveToolbarActionProviderInterface``. The interface is autoconfigured with the tag
``sulu_content.save_toolbar_action_provider``, so a provider that implements it needs nothing else:

.. code-block:: php

    <?php

    use Sulu\Bundle\AdminBundle\Admin\View\ToolbarAction;
    use Sulu\Content\Infrastructure\Sulu\Admin\SaveToolbarActionProviderInterface;

    class ScheduleSaveToolbarActionProvider implements SaveToolbarActionProviderInterface
    {
        public function getToolbarActions(string $contentRichEntityClass): array
        {
            return [
                new ToolbarAction('app.schedule', [
                    'label' => 'app.schedule',
                    'permission' => 'live',
                ]),
            ];
        }
    }

The provider is asked once per content form. It returns nothing for the entity types it does not handle. A bundle that is
not installed adds nothing, so the entry only shows where the bundle is.

The option ``permission`` is optional. It is ``edit`` or ``live``, any other value throws. An entry that has it also gets
the condition the form uses for that permission. On pages, this is the permission on the webspace. A provider cannot know
it. Without the option, the entry shows for everybody who sees the dropdown. Every entry is hidden while a request is
open.

An entry may be any ``ToolbarAction`` subclass, for example ``SaveWithFormDialogToolbarAction``. The class is kept, so
its serializer subscribers still run. Only the options change: ``visible_condition`` is combined with the permission
condition and the rule that hides the entry while a request is open.

.. note::

    The interface is marked ``@experimental``.

Saving and Creating an Item
~~~~~~~~~~~~~~~~~~~~~~~~~~~

The toolbar action ``sulu_admin.save_and_create`` saves the form as a draft. Then it creates an item of another
resource, with the values of a dialog form and the id and the locale of the saved content. Use it to schedule something
for the content, for example a publish at a date. It needs no admin JavaScript of your own. Build it with the class
``SaveAndCreateToolbarAction``:

.. code-block:: php

    use Sulu\Bundle\AdminBundle\Admin\View\SaveAndCreateToolbarAction;

    new SaveAndCreateToolbarAction(
        label: 'app.publish_at',
        title: 'app.publish_at',
        formKey: 'app_publish_at',
        resourceKey: 'tasks',
        idParameter: 'entityId',
        data: ['handlerClass' => PagePublishTaskHandler::class],
        parameters: ['entityClass' => PageInterface::class],
        permission: 'live',
    );

.. list-table::
    :header-rows: 1
    :widths: 25 75

    * - Option
      - Meaning
    * - ``formKey``
      - Form of the dialog. Its values are sent as the body of the request.
    * - ``resourceKey``
      - Resource that gets the new item, sent as ``POST``.
    * - ``label``, ``title``
      - Translation keys of the entry and of the dialog title. The server translates them in the domain ``admin``.
    * - ``data``
      - Values that are sent along with the values of the dialog.
    * - ``parameters``
      - Query parameters that are sent along.
    * - ``idParameter``, ``localeParameter``
      - Names of the query parameters for the id and the locale of the saved content. ``idParameter`` is required and
        must not be ``id``. ``localeParameter`` is ``locale`` by default.
    * - ``visibleCondition``
      - Condition that hides the entry, sent as ``visible_condition``. The action checks it itself, so it also works
        inside the dropdown.
    * - ``permission``
      - ``edit`` or ``live``, see the section above.

The entry saves the form first, so a content that is new gets its id before the item is created.

Checking the Publish Permission for a User
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Code without a token, such as a console command or a ``task:run`` job, is let through by the publish check. To check a
user, pass it as the optional fourth argument of ``WorkflowTransitionAdminAuthorizerInterface::assertCanPublish()``:

.. code-block:: php

    $authorizer->assertCanPublish('pages', $id, 'en', $user);

``$user`` is a ``Sulu\Component\Security\Authentication\UserInterface``. The permissions come from the roles of the user.
The rule is the same as in the admin: ``live``, or ``edit`` with an approved request. A user without it causes an
``AccessDeniedException``. A user that is locked or disabled is refused as well. The check runs in the system ``Admin::SULU_ADMIN_SECURITY_SYSTEM`` unless the security
condition names another one. Without a user, the current token decides, as before.

.. note::

    The interface is marked ``@internal``. The authorizer is only registered in the admin context, so ``bin/console``
    and ``bin/adminconsole`` can inject it. The website kernel and ``bin/websiteconsole`` cannot.

.. _Symfony Messenger documentation: https://symfony.com/doc/current/messenger.html#routing-messages-to-a-transport
