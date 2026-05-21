Two-Factor Authentication
=========================

Sulu allows to use two-factor authentication over email via the `SchebTwoFactorBundle`_ packages. To enable it,
the packages need to be installed via composer for the project:

.. code-block:: bash

    composer require scheb/2fa-bundle scheb/2fa-email scheb/2fa-trusted-device

.. note::

    Currently, only the code-by-email authentication method is supported.

The security configuration needs to be adjusted to allow two-factor authentication in the
admin firewall. This is configured in ``config/packages/security.yaml``:

.. code-block:: diff

    security:
        # ...

        access_control:
            # ...
            - { path: ^/admin/login$, roles: PUBLIC_ACCESS }
   +         - { path: ^/admin/2fa, role: PUBLIC_ACCESS }
            # ...
        firewalls:
            # ...
            admin:

                # ...
                logout:
                    path: sulu_admin.logout
   +             two_factor:
   +                 prepare_on_login: true
   +                 prepare_on_access_denied: true
   +                 check_path: 2fa_login_check_admin
   +                 authentication_required_handler: sulu_security.two_factor_authentication_required_handler
   +                 success_handler: sulu_security.two_factor_authentication_success_handler
   +                 failure_handler: sulu_security.two_factor_authentication_failure_handler

Afterwards, the scheb/2fa bundle needs to be configured to enable email and trusted devices in ``config/packages/scheb_2fa.yaml``:

.. code-block:: yaml

    scheb_two_factor:
        email:
            enabled: true
            sender_email: "%env(SULU_ADMIN_EMAIL)%"
        trusted_device:
            enabled: true

Additionally, the routes of the scheb/2fa bundle must be added to the project in ``config/routes/scheb_2fa.yaml``:

.. code-block:: yaml

    # For Admin:
    2fa_login_check_admin:
        path: /admin/2fa_check

After the configuration has been updated and the Symfony cache has been cleared, each logged-in user can enable two-factor authentication in their profile settings in the administration interface.


Enforce Two-Factor Authentication
---------------------------------

Two-factor authentication can be enforced based on the user's email address by configuring a regular expression. If the user's email matches the pattern, the `code-by-email` two-factor authentication method is enabled for that user.

This is configured in ``config/packages/sulu_security.yaml``:

.. code-block:: diff

    sulu_security:
        # ...

    +    two_factor:
    +        force:
    +            enabled: true
    +            pattern: '/^.+@.+\..+$/gm' # Simple regex that checks if the email address contains an @ and a TLD.

        # ...

.. note::

    This is only enforced for newly created user accounts. Existing user accounts must enable it manually via their profile settings.

.. _SchebTwoFactorBundle: https://symfony.com/bundles/SchebTwoFactorBundle/
