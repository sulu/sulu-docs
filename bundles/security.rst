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
denied. Have a look at :doc:`../cookbook/securing-your-application` to see an
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

.. _security mechanisms of Symfony: http://symfony.com/doc/current/book/security.html
