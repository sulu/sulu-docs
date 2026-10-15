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

.. _Symfony Messenger documentation: https://symfony.com/doc/current/messenger.html#routing-messages-to-a-transport
