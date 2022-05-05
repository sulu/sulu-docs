Password policy
===============

The SecurityBundle allows to define a password policy by configuring a pattern.
The pattern will be used to validate the input of the user in the administration interface
and programatically created users in the `UserManager`.

The following example enables the default pattern of sulu (minimum length of 8 characters).

.. code:: yaml

    # file: config/packages/sulu_security.yaml

    sulu_security:
        password_policy:
            enabled: true

The configuration below sets an example pattern that validates the password against following constraints:

* The password length must be greater than or equal to 8
* The password must contain one or more uppercase characters
* The password must contain one or more lowercase characters
* The password must contain one or more numeric values
* The password must contain one or more special characters

.. code:: yaml

    # file: config/packages/sulu_security.yaml

    sulu_security:
        password_policy:
            enabled: true
            pattern: '(?=^.{8,}$)(?=.*\d)(?=.*[^a-zA-Z0-9]+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$'
            info_translation_key: app.password_information

Dont forget to provide a translation that explains your password policy to the user by setting the ``info_translation_key`` configuration. 

.. code:: json

    {
        "app.password_information": "Passwords have a minimum length of 8 characters and must contain one or more uppercase characters, one or more lowercase characters, one or more numeric values, one or more special characters."
    }
