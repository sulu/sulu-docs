Two-Factor Authentication
=========================

Sulu allows to use two-factor authentication over email via the scheb/2fa packages. To enable it,
the packages need to be installed into the project via composer:

.. code-block:: bash

    composer require scheb/2fa-bundle scheb/2fa-email scheb/2fa-trusted-device

The security configuration needs to be adjusted to allow two-factor authentication in the
admin firewall. This is configured in the ``config/packages/security.yaml``:

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

Afterwards, the scheb/2fa bundle needs to be configured to enable email and trusted devices
in the ``config/packages/scheb_2fa.yaml`` file:

.. code-block:: yaml

    scheb_two_factor:
        email:
            enabled: true
            sender_email: "%env(SULU_ADMIN_EMAIL)%"
        trusted_device:
            enabled: true

Additionally, the routes of the scheb/2fa bundle must be added to the project in
the ``config/routes/scheb_2fa.yaml`` file:

.. code-block:: yaml

    # For Admin:
    2fa_login_check_admin:
        path: /admin/2fa_check

Finally, after adjusting the configuration and clearing the symfony cache, it is possible to enable
two-factor authentication via the administration interface in the profile of the logged-in user.
