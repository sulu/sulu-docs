Show Errors of the Server at the Form Fields
============================================

Available since Sulu 3.1. When the server rejects the data of a form, the admin shows the message below the field it is
about. The label and the border of the field turn red, and a field can have several messages. A message without a
field is shown in the toolbar, like every other error.

The admin reads a ``violations`` list in the response, which has the structure the Symfony serializer creates for the
violations of the validator:

.. code-block:: json

    {
        "violations": [
            {"propertyPath": "name", "title": "The name is already in use."},
            {"propertyPath": "blocks[0].key", "title": "This value is not valid."}
        ]
    }

A ``propertyPath`` is the path to the field in the notation of the validator. ``name`` is a field, ``blocks[0].key`` is
the ``key`` of the first block and ``[blocks][0][key]`` is the same field of an array. The same path can be used
several times for a field with several messages. An empty path belongs to no field, and its message is shown in the
toolbar.

There are two ways to fill the list.

.. note::

    ``FieldErrorsExceptionInterface`` is marked as ``@experimental`` and can change in a minor release.

Violations of the Validator
---------------------------

The violations of a ``ValidationFailedException`` are added to the response. Throw it as the previous exception of an
HTTP exception with the status code ``422``:

.. code-block:: php

    <?php

    use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
    use Symfony\Component\Validator\Exception\ValidationFailedException;

    $violations = $this->validator->validate($data);

    if (0 < \count($violations)) {
        throw new UnprocessableEntityHttpException(
            'Invalid form data',
            new ValidationFailedException($data, $violations)
        );
    }

Symfony does the same for the argument of a controller with ``#[MapRequestPayload]``, so its violations reach the form
too. A ``ValidationFailedException`` that is not wrapped answers with the status code ``500``.

The messages of the validator are shown as they are, a validator translates them already. Nothing is validated
automatically: the exception only carries the result of your own check to the form.

Exception of the Domain
-----------------------

An exception that names the fields it is about implements ``FieldErrorsExceptionInterface``. It is usually combined with
``TranslationErrorMessageExceptionInterface``, which controls the message of the whole response:

.. code-block:: php

    <?php

    declare(strict_types=1);

    namespace App\Exception;

    use Sulu\Component\Rest\Exception\FieldErrorsExceptionInterface;
    use Sulu\Component\Rest\Exception\TranslationErrorMessageExceptionInterface;

    class SlugAlreadyExistsException extends \Exception implements
        TranslationErrorMessageExceptionInterface,
        FieldErrorsExceptionInterface
    {
        public function __construct(private readonly string $slug)
        {
            parent::__construct(\sprintf('The slug "%s" already exists.', $slug), 1201);
        }

        public function getMessageTranslationKey(): string
        {
            return 'app.slug_in_use';
        }

        public function getMessageTranslationParameters(): array
        {
            return ['{slug}' => $this->slug];
        }

        public function getFieldErrors(): array
        {
            return [
                ['propertyPath' => 'slug', 'key' => 'app.slug_in_use', 'parameters' => ['slug' => $this->slug]],
            ];
        }
    }

Instead of a ``key`` an entry can have a ``title`` with a text that is translated already. A ``key`` is a translation
key of the ``admin`` domain, and the ``parameters`` are replaced in the message as ``{slug}``. Add the translation to
``translations/admin.en.json`` of your application:

.. code-block:: json

    {
        "app.slug_in_use": "The slug \"{slug}\" is already in use."
    }

The exception needs a status code, for example through the exception mapping of the FOSRestBundle or by extending an
HTTP exception such as ``ConflictHttpException``. The response then contains the ``violations`` with the translated
``title``.

What the Admin Does
-------------------

The forms of the admin, their overlays and the permission overlay of the media collections show the ``violations`` of a
failed ``POST`` or ``PUT``. The messages of a field disappear when the user changes the field, and all of them
disappear after a successful save. Nothing has to be done in JavaScript.

A few things to keep in mind:

* A path that is not a field of the form, or a field on another tab, is not shown at a field. The toolbar then only
  shows that the form contains invalid values.
* A controller that catches the exception and builds its own response does not reach the ``violations``. Let the
  exception through.
* A custom form store that does not extend ``ResourceFormStore`` passes the ``violations`` of a failed request to
  ``setServerErrors()`` itself.
