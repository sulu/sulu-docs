Password policy
===============

The SecurityBundle provides a way to set the password policy by providing a pattern.
This pattern will be used to validate the input of the user in the form and also in the
`UserManager`.

The following example enables the default pattern of sulu (minimum length of 8 characters).

.. code:: yaml

    # file: config/packages/sulu_security.yaml

    sulu_security:
        password_policy:
            enabled: true

The following configuration provides a major pattern which check the password for:

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
            information_translation_key: app.password_information

Dont forget to also add your own translation for password information which should contain your requirements.
See following example:

.. code:: json

    {
        "app.password_information": "You password should contain one or more uppercase characters, one or more lowercase characters, one or more numeric values, one or more special characters and the minimum length is 8 character."
    }
