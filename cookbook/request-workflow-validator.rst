Add a Check to the Request Workflow
===================================

Available since Sulu 3.1. A :doc:`request workflow <../bundles/content/request-workflow>` can run checks on content
before it is published. A *pre-validator* blocks the transition synchronously. A *validator* runs after the request was
created and does not hold up the editor. Both return a ``ValidationResult``.

Pre-validator
-------------

A pre-validator implements ``RequestWorkflowPreValidatorInterface``. It receives the content that is about to be
requested or published and must not change it:

.. code-block:: php

    <?php

    declare(strict_types=1);

    namespace App\RequestWorkflow;

    use Sulu\Content\Application\RequestWorkflow\PreValidator\PreValidationContext;
    use Sulu\Content\Application\RequestWorkflow\PreValidator\RequestWorkflowPreValidatorInterface;
    use Sulu\Content\Application\RequestWorkflow\Validator\ValidationResult;
    use Sulu\Content\Domain\Model\TemplateInterface;
    use Sulu\Content\Domain\Model\WorkflowTransitionRequest\WorkflowTransitionRequestDecisionMessage;

    class TitleLengthPreValidator implements RequestWorkflowPreValidatorInterface
    {
        public static function getKey(): string
        {
            return 'title_length';
        }

        public function check(PreValidationContext $context): ValidationResult
        {
            $content = $context->dimensionContent;
            $title = $content instanceof TemplateInterface ? ($content->getTemplateData()['title'] ?? '') : '';

            if (\mb_strlen($title) <= $context->config['max']) {
                return ValidationResult::approve();
            }

            return ValidationResult::reject(
                WorkflowTransitionRequestDecisionMessage::translated(
                    'app.title_too_long',
                    ['max' => $context->config['max']],
                )
            );
        }
    }

A failed pre-validator answers ``422`` and the admin shows its messages to the editor. It also runs when a user with the
``live`` permission publishes directly. Add an entry for a translation key to the admin translations of your
application, for example ``"app.title_too_long": "The title is longer than {max} characters."`` in
``translations/admin.en.json``. The admin names the check by the translation key
``sulu_content.workflow_transition_request.pre_validators.<key>`` (``validators.<key>`` for a validator) and shows the
key itself without one.

Validator
---------

A validator implements ``RequestWorkflowValidatorInterface``. It gets the identity of the content instead of the content
and runs after the request was saved:

.. code-block:: php

    <?php

    declare(strict_types=1);

    namespace App\RequestWorkflow;

    use Sulu\Content\Application\RequestWorkflow\Validator\RequestWorkflowValidatorInterface;
    use Sulu\Content\Application\RequestWorkflow\Validator\ValidationContext;
    use Sulu\Content\Application\RequestWorkflow\Validator\ValidationResult;
    use Sulu\Content\Domain\Model\WorkflowTransitionRequest\WorkflowTransitionRequestDecisionMessage;

    class BrokenLinksValidator implements RequestWorkflowValidatorInterface
    {
        public function __construct(private readonly LinkChecker $linkChecker)
        {
        }

        public static function getKey(): string
        {
            return 'broken_links';
        }

        public function check(ValidationContext $context): ValidationResult
        {
            $links = $this->linkChecker->findBroken($context->resourceKey, $context->resourceId, $context->locale);

            if ([] === $links) {
                return ValidationResult::approve();
            }

            return ValidationResult::reject(
                ...\array_map(WorkflowTransitionRequestDecisionMessage::text(...), $links)
            );
        }
    }

``ValidationContext`` contains ``resourceKey``, ``resourceId``, ``locale``, ``workflowName`` and ``validatorConfig``.
A validator must not write, because the check can be run again. An exception is recorded as a rejection of the check.
In a Messenger worker this happens after the retries are used up.

``ValidationResult::reject()`` needs at least one message. A message is either a translation key with parameters,
created with ``WorkflowTransitionRequestDecisionMessage::translated()``, or plain text, created with
``WorkflowTransitionRequestDecisionMessage::text()``.

Register and Configure
----------------------

With the default `services.yaml configuration from Symfony`_ and autoconfiguration, the classes are tagged
automatically with ``sulu_content.request_workflow_pre_validator`` and ``sulu_content.request_workflow_validator``.
Otherwise tag the service yourself. The ``key`` attribute of the tag overrides ``getKey()``.

Then add the key to a workflow. The value is handed to the check as its config:

.. code-block:: yaml

    # config/packages/sulu_content.yaml
    sulu_content:
        request_workflows:
            default:
                pre_validators:
                    title_length:
                        max: 70
                validators:
                    broken_links:
                        required: true

``required: true`` is read by Sulu and not passed to the validator. A required validator keeps the request pending until
it passes. A validator added later does not run for requests that are already open.

.. _services.yaml configuration from Symfony: https://symfony.com/doc/current/service_container.html#service-container-services-load-example
