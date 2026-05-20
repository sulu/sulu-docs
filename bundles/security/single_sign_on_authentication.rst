Single-Sign-On Authentication
=============================

Sulu supports authentication via Single-Sign-On (SSO).
To enable it, the security configuration needs to be adjusted to allow SSO in the admin firewall.
This can be configured in the ``config/packages/security.yaml``:

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

    # ...
    sulu_security:
        checker:
            enabled: true
        password_policy:
            enabled: true
   +    single_sign_on:
   +        providers:
   +            'sulu.io':
   +                dsn: 'openid://%env(resolve:SULU_OPEN_ID_CLIENT_ID)%:%env(resolve:SULU_OPEN_ID_CLIENT_SECRET)%@%env(resolve:SULU_OPEN_ID_ENDPOINT)%'
   +                default_role_key: 'USER'

After adjusting the configuration and clearing the symfony cache,
you only see the ``username or email`` field when you try to login to the administration interface.
When the user email matches the configured domain,
the user is then redirected to the SSO provider to authenticate. After successful authentication, the system redirects the user back to the administration interface.
If the domain does not match the configured domain, the user is authenticated using the standard login form.
On password reset, when the domain matches, the user is also redirected to the SSO provider.

Enable SSO provider:
Before enabling the SSO provider, ensure you've created a role with the key USER, or set a key for your preferred default role and use it for the parameter ``default_role_key``.

Redirect URL:
If your provider requires a redirect URL, provide your admin URL, e.g., ``sulu.io/admin``.

.. note::

    At the moment, only the OpenID protocol is supported for Single-Sign-On authentication in Sulu.
