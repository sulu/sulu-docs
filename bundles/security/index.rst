SecurityBundle
==============

The SuluSecurityBundle is responsible for protecting different data and areas
of the application. Therefore it makes use and enhances the standard `security
mechanisms of Symfony`_.

Structure
---------

Every Sulu user is linked to a specific Sulu contact from the
SuluContactBundle. In addition to that a user can have some roles and groups
assigned, whereby a group can consist of multiple other groups and roles.

Every role has to be part of a certain system. Different systems can be
registered via the security contexts, which are explained later. These systems
correspond to different applications handled by Sulu. So by default there is
only the ``Sulu`` system. A user is only enabled to login into Sulu, if he has
at least one role with the ``Sulu`` system assigned.

The users provide two different flags. The ``locked`` flag signalizes that a
user has been locked by an administrator for some reason. As soon as this flag
is set, the user can't login to the system anymore. The second flag is called
``enabled``, and will be set to true by default. This flag is only important if
you have implemented your own registration process. In the case you want to use
a double opt-in mechanism you can set this flag to false on the registration,
and toggle it, e.g. when the user clicks on a link in an email. As long as the
``enabled`` flag is set to false, the Sulu-Admin offers you a button to enable
the user.

Security contexts
-----------------

Every application can define its own security contexts, which will then be
available in the list of security contexts, on which access can be granted or
denied. Have a look at :doc:`../../cookbook/securing-your-application` to see an
example.

These contexts are used to control the access to different areas of the
application, e.g. if the user is allowed to edit content at all.

The following permissions are distinguished:

.. list-table::

    * - ``VIEW``
      - Permission to see data the given context
    * - ``ADD``
      - Permission to add new data to the given context
    * - ``EDIT``
      - Permission to edit already existing data in the given context
    * - ``DELETE``
      - Permission to delete data in the given context
    * - ``ARCHIVE``
      - Permission to archive data in the given context
    * - ``LIVE``
      - Permission to publish data in the given context
    * - ``SECURITY``
      - Permission to grant or deny access on data in the given context

All the permission values are encoded in a bitmask and saved in a permission
object, which has a link to a role. This way it is easily possible to evaluate
if a user has access to a security context by checking if one of his roles
grants access.

Access Control Manager
----------------------

The ``AccessControlManager`` is responsible to set permissions on specific
objects. Since this is not totally decoupled from the entity being protected,
there is the possibility to register multiple ``AccessControlProvider``. This
is simply a service implementing the ``AccessControlProviderInterface`` tagged
with ``sulu.access_control``.

The task of this class is to save the permission information into the correct
database. This is important, because otherwise it would not be possible to
paginate lists considering permissions of these entities in an easy and
performant way. There are already two implementations of the
``AccessControlProviderInterface``, the ``PhpcrAccessControlProvider`` handling
the permission storing for PHPCR and therefore for our content section, and the
``DoctrineAccessControlProvider``, which can be used in combination with any
Doctrine entity. The entity only has to implement the
``SecuredEntityInterface`` to signalize that it can be used with the
``DoctrineAccessControlProvider``.

.. note::

    The ``AccessControlManager`` is used by some other components, especially
    by the ``PermissionController``, which handles the requests from the
    reusable permission tab, and the ``SecurityContextVoter`` from Sulu.

Checking security
-----------------

Sulu offers a ``SecurityChecker`` enabling the developer to easily check if
a given ``SecurityCondition`` is granted for the currently logged in user. The
security condition consists of the already mentioned security context, an
object type and id for decoupling from real objects for performance reasons,
and the locale to check the permission in. The ``SecurityChecker`` uses the
Symfony ``AccessDecisionManager``, which calls all security voters including
the ``SecurityContextVoter``.

This voter will check if the user is allowed to perform the given action (the
permissions already listed above) in the given context in the given locale. If
the object type and id are also passed the permissions of the security contexts
from the role might be overridden by the permissions from this specific object
(which are handled by the previously mentioned ``AccessControlManager``).

Single-Sign-On  Authentication
------------------------------

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
   +                user_role: 'USER'

After adjusting the configuration and clearing the symfony cache,
you only see the ``username or email`` field when you try to login to the administration interface.
When the user email matches the configured domain,
the user is then redirected to the SSO provider to authenticate and after successful authentication,
the user is redirected back to the administration interface.
If the domain does not match the configured domain, the user is authenticated using the standard login form.
On password reset, when the domain matches, the user is also redirected to the SSO provider.

Note: At the moment, only the OpenID protocol is supported for Single-Sign-On authentication in Sulu.

Two-Factor Authentication
-------------------------

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


.. _security mechanisms of Symfony: http://symfony.com/doc/current/book/security.html

.. toctree::
    :maxdepth: 2

    security_system
    password_policy
