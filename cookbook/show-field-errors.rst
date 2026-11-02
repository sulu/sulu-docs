Show Errors of the Server at the Form Fields
============================================

Available since Sulu 3.1. When the server rejects the data of a form, the admin shows the message below the field it is
about. The label and the border of the field turn red, and a field can have several messages. A message without a
field is shown in the toolbar, like every other error.

.. note::

    The interface and the exception described here are marked as ``@experimental`` and can change in a minor release.

Throw an Exception
------------------

Throw a ``FieldErrorsException`` with a ``path`` and a ``title`` for each message. It answers with the status code
``422``:

.. code-block:: php

    <?php

    use Sulu\Component\Rest\Exception\FieldErrorsException;

    throw new FieldErrorsException([
        ['path' => 'name', 'title' => 'The name is already in use.'],
        ['path' => 'name', 'title' => 'The name must not contain a slash.'],
        ['path' => 'blocks/0/key', 'title' => 'The key is already in use.'],
    ]);

The ``path`` is the position of the field in the data of the form, as a JSON pointer without the leading slash:
``name`` for a field, ``blocks/0/key`` for a field in the first block and ``2/amount`` for the third entry of a
list. The same path can be used several times. An empty path belongs to no field, and the message is shown in the
toolbar.

Instead of a translated ``title`` an entry can name a translation key of the ``admin`` domain with parameters:

.. code-block:: php

    <?php

    throw new FieldErrorsException([
        ['path' => 'name', 'key' => 'app.name_in_use', 'parameters' => ['name' => $name]],
    ]);

.. code-block:: json

    {
        "app.name_in_use": "The name \"{name}\" is already in use."
    }

Add the translations to ``translations/admin.en.json`` of your application. A parameter is replaced in the message
as ``{name}``.

Validator Violations
--------------------

``FieldErrorsException::fromConstraintViolationList()`` turns the result of the Symfony validator into field errors. The
property path of a violation (``blocks[0].key``) becomes the path of the field (``blocks/0/key``), and the message of the
violation is used as it is:

.. code-block:: php

    <?php

    use Sulu\Component\Rest\Exception\FieldErrorsException;

    $violations = $this->validator->validate($data);

    if (0 < \count($violations)) {
        throw FieldErrorsException::fromConstraintViolationList($violations);
    }

Nothing is validated automatically. The exception only carries the result of your own check to the form.

Your Own Exception
------------------

An exception that already exists in your code can implement ``FieldErrorsExceptionInterface`` instead:

.. code-block:: php

    <?php

    declare(strict_types=1);

    namespace App\Exception;

    use Sulu\Component\Rest\Exception\FieldErrorsExceptionInterface;
    use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

    class SlugAlreadyExistsException extends ConflictHttpException implements FieldErrorsExceptionInterface
    {
        public function __construct(private readonly string $slug)
        {
            parent::__construct(\sprintf('The slug "%s" already exists.', $slug));
        }

        public function getFieldErrors(): array
        {
            return [
                ['path' => 'slug', 'key' => 'app.slug_in_use', 'parameters' => ['slug' => $this->slug]],
            ];
        }
    }

The response keeps the status code of the exception and gets a ``fieldErrors`` list with the translated messages, next to
the usual fields of the error response:

.. code-block:: json

    {
        "fieldErrors": [
            {"path": "slug", "title": "The slug \"about\" is already in use."}
        ]
    }

What the Admin Does
-------------------

The forms of the admin, their overlays and the permission overlay of the media collections show the ``fieldErrors`` of
a failed ``POST`` or ``PUT``. The messages of a field disappear when the user changes the field, and all of them
disappear after a successful save. Nothing has to be done in JavaScript.

A few things to keep in mind:

* A path that is not a field of the form, or a field on another tab, is not shown at a field. The toolbar then only
  shows that the form contains invalid values.
* A controller that catches the exception and builds its own response does not reach the ``fieldErrors``. Let the
  exception through.
* A custom form store that does not extend ``ResourceFormStore`` passes ``fieldErrors`` of a failed request to
  ``setServerErrors()`` itself.
