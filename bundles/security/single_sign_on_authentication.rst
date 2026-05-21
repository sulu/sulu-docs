Single-Sign-On Authentication
=============================

Sulu supports authentication via Single-Sign-On (SSO).

.. note::

    Single Sign-On authentication in Sulu currently supports only the OpenID protocol.

To enable it, the security configuration needs to be adjusted to allow SSO in the admin firewall.
This can be configured in ``config/packages/security.yaml``:

.. code-block:: diff

    security:
        # ...

        firewalls:
            # ...
            admin:

                # ...
                logout:
                    path: sulu_admin.logout
   +            access_token:
   +                token_handler: sulu_security.single_sign_on_token_handler
   +                token_extractors: sulu_security.single_sign_on_token_extractor

Furthermore, the domains that should use Single Sign-On must be configured. This is done in ``config/packages/sulu_security.yaml``:

.. code-block:: diff

    sulu_security:
        # ...
        password_policy:
            enabled: true
   +    single_sign_on:
   +        providers:
   +            'sulu.io':
   +                dsn: 'openid://%env(resolve:SULU_OPEN_ID_CLIENT_ID)%:%env(resolve:SULU_OPEN_ID_CLIENT_SECRET)%@%env(resolve:SULU_OPEN_ID_ENDPOINT)%'
   +                default_role_key: 'USER'

After adjusting the configuration and clearing the Symfony cache, you only see the ``username or email`` field when you try to login to the administration interface.

When the user email matches the configured domain, the user is then redirected to the SSO provider to authenticate. After successful authentication, the system redirects the user back to the administration interface.

If the domain does not match the configured domain, the user is authenticated using the standard login form.

On password reset, when the domain matches, the user is also redirected to the SSO provider.

**Redirect URL:**
If your provider requires a redirect URL, provide your admin URL, e.g., ``sulu.io/admin/``.

.. note::

    The trailing slash at the end of the redirect URL is required for providers like Microsoft Entra, as they validate the exact redirect URL.


Configure user roles
--------------------

Before enabling the SSO provider, ensure that a role with the key ``USER`` exists. If a different role should be assigned by default, configure its key via the ``default_role_key`` parameter.

Role keys can be managed in the administration interface under ``Settings > User roles > [ROLE] > Key``.
