Assignment 6 — Allow for event registration on the website
##########################################################

Visitors should **register** for an event on the **public** event page. This is **standard Symfony**: a **Doctrine**
entity for the registration, a **form type**, and a **website controller** that accepts GET (show form) and POST (save).

.. note::

   Code is taken from ``assignment/06``.

What you learn
==============

* `Mapping an entity <https://symfony.com/doc/current/doctrine.html#creating-an-entity-class>`__ and a **ManyToOne** to
  ``Event``.
* `Building a form <https://symfony.com/doc/current/forms.html>`__ with ``AbstractType`` and validation.
* Using ``TemplateAttributeResolverInterface`` (Sulu’s website layer) so the page still receives the normal **Sulu**
  template variables (SEO, content, …) when you render your own route. It is the same service the
  ``DefaultController`` uses to prepare Twig.

Prerequisites
=============

* The ``Event`` entity and the ``app.event`` route area from the workshop base project.

Step 1 — Create ``EventRegistration`` and the relation
========================================================

Use the MakerBundle or write the class by hand. The reference stores **firstName**, **lastName**, **email**, an optional
**message**, and a **required** ``ManyToOne`` to ``Event`` with **cascade** on delete:

.. code-block:: php

    // src/Entity/EventRegistration.php
    <?php

    declare(strict_types=1);

    namespace App\Entity;

    use Doctrine\DBAL\Types\Types;
    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;

    #[ORM\Entity(repositoryClass: EventRegistrationRepository::class)]
    class EventRegistration
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: Types::INTEGER)]
        private ?int $id = null;

        #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
        #[Assert\NotBlank]
        private ?string $firstName = null;

        #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
        #[Assert\NotBlank]
        private ?string $lastName = null;

        #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
        #[Assert\NotBlank]
        #[Assert\Email]
        private ?string $email = null;

        #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
        private ?string $message = null;

        public function __construct(
            #[ORM\ManyToOne(targetEntity: Event::class)]
            #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
            private Event $event,
        ) {
        }

        // getters and setters for all properties ...
    }

Then update the schema (workshop shortcut):

.. code-block:: bash

   $ bin/adminconsole doctrine:schema:update --force
   $ bin/adminconsole doctrine:schema:validate

In production, prefer `Doctrine migrations <https://symfony.com/doc/current/doctrine.html#migrations>`__.

Step 2 — Form type
==================

.. code-block:: php

    // src/Form/EventRegistrationType.php
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('message');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => EventRegistration::class]);
    }

Step 3 — Website controller
===========================

The route is ``/{_locale}/event/{id}`` (name ``app.event``). The controller loads the ``Event`` in the current locale,
creates a new ``EventRegistration`` bound to that event, **handles** the form, and on success **redirects** with a
``?success=1`` query flag.

.. code-block:: php

    // src/Controller/Website/EventWebsiteController.php (excerpt)
    #[Route('/{_locale}/event/{id}', name: 'app.event')]
    public function indexAction(int $id, Request $request): Response
    {
        $event = $this->eventRepository->findById($id, $request->getLocale());
        if (!$event instanceof Event) {
            throw new NotFoundHttpException();
        }

        $registration = $this->eventRegistrationRepository->create($event);
        $form = $this->createForm(EventRegistrationType::class, $registration);
        $form->add('submit', SubmitType::class, ['label' => 'Create']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->eventRegistrationRepository->save($registration);

            return $this->redirectToRoute('app.event', [
                'id' => $event->getId(),
                'success' => true,
            ]);
        }

        return $this->render('events/index.html.twig', $this->templateAttributeResolver->resolve([
            'event' => $event,
            'success' => $request->query->get('success'),
            'form' => $form->createView(),
            'content' => ['title' => $event->getTitle()],
        ]));
    }

``TemplateAttributeResolverInterface::resolve()`` merges your variables with the usual Sulu **website** defaults (so SEO
and layout still work).

Step 4 — Twig
=============

.. code-block:: twig

    <!-- templates/events/index.html.twig (excerpt) -->
    {% form_theme form 'bootstrap_4_layout.html.twig' %}

    <div class="container">
        {% if success %}
            <div class="success">
                <b>Thanks for your registration.</b>
            </div>
        {% else %}
            <h2>Register for this event:</h2>
            {{ form(form) }}
        {% endif %}
    </div>

See also
========

* `Forms in Symfony (best practices) <https://symfony.com/doc/current/best_practices/forms.html>`__
* :doc:`../cookbook/custom-controller` (for the *Sulu* side of custom controllers, though this route is plain Symfony)

Reference branch
================

``assignment/06`` — entity, form, controller, repository, template.
