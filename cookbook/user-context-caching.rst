User context caching
====================

HTTP caching does not work out of the box when a session comes into play.
Fortunately the FOSHttpCacheBundle has a feature called `User Context`_.

In this document we will quickly go through the steps necessary to activate the
User Context feature in Sulu.

.. note::

    For more information about this topic visit the `User Context`_ page in the
    FOSHttpCacheBundle documentation.

1. Uncomment the ``user_context_hash`` route in `config/routes/fos_http_cache.yaml`

.. code-block:: yaml

    user_context_hash:
        path: /_fos_user_context_hash

2. Uncomment the ``user_context`` session in `config/packages/fos_http_cache.yaml`

.. code-block:: yaml

    fos_http_cache:
        user_context:
            enabled: true
            role_provider: true
            hash_cache_ttl: 0

3. Uncomment the registration of the  ``UserContextListener`` in `src/Kernel.php`

.. code-block:: php
 
    <?php

    class Kernel extends SuluKernel implements HttpCacheProvider
    {
        public function getHttpCache()
        {
            if (!$this->httpCache) {
                $this->httpCache = new SuluHttpCache($this);
                // Activate the following for user based caching see also:
                // https://foshttpcachebundle.readthedocs.io/en/latest/features/user-context.html
                //
                $this->httpCache->addSubscriber(
                    new \FOS\HttpCache\SymfonyCache\UserContextListener([
                        'session_name_prefix' => 'SULUSESSID',
                    ])
                );
            }

            return $this->httpCache;
        }
    }

4. Uncomment the ``security`` configuration in `config/packages/security_website.yaml`

.. note::

    The Symfony Security component is used here, which has its own, very detailed,
    `documentation`_.

.. code-block:: yaml

    security:
        encoders:
            Sulu\Bundle\SecurityBundle\Entity\User: bcrypt

        providers:
            sulu:
                id: sulu_security.user_provider

        firewalls:
            website:
                pattern: ^/
                anonymous: true
                lazy: true
                form_login:
                    login_path: login
                    check_path: login
                logout:
                    path: logout
                    target: /
                remember_me:
                    secret:   "%kernel.secret%"
                    lifetime: 604800 # 1 week in seconds
                    path:     /

    sulu_security:
        checker:
            enabled: true

5. Make sure that Symfony's ``SecurityBundle`` is registered for all contexts
   (by default it is only registered for the admin context) in `config/bundles.php`

.. code-block:: php

    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],

6. Add the login and logout routes to `config/routes_website.yaml`

.. code-block:: yaml

    login:
        path: /login
        controller:   Symfony\Bundle\FrameworkBundle\Controller\TemplateController
        defaults:
            template: static/login.html.twig

    logout:
        path: /logout

7. Implement the template for the login form in `static/login.html.twig`

.. code-block:: twig

    <form action="{{ path('login') }}" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="_username" />

        <label for="password">Password:</label>
        <input type="password" id="password" name="_password" />

        <button type="submit">login</button>
    </form>

.. note::

    The previous two steps could also be replaced by the `SuluCommunityBundle`_,
    which helps you with creating login and registration forms. Additionally it
    helps you with creating opt-in emails and other tasks.

.. _User Context: https://foshttpcachebundle.readthedocs.io/en/latest/features/user-context.html
.. _documentation: https://symfony.com/doc/current/security.html
.. _SuluCommunityBundle: https://github.com/sulu/SuluCommunityBundle
