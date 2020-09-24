HTTPS
=====

Sulu behave the same way as Symfony when generating urls.
If the requested protocol is ``https`` it will use automatically ``https`` to generate its urls.

If this does not happen its a hint that you are behind a load balancer or have a proxy between your request server.
In that case you should have a look at the `Symfony Proxy Documentation`_ and configure your trusted proxy ip addresses.

If it still doesn't work you should debug the Symfony `Request::isSecure`_  method,
which represents which protocol is used by Symfony in its `Request::getScheme`_  method.

It is also possible to force the protocol in your vhost of your websebserver:

Apache
------

Add the following to your apache vhost:

.. code-block:: apache

    SetEnv HTTPS on

Nginx
-----

Add the following to your nginx vhost php-fpm param configuration:

.. code-block:: nginx

    fastcgi_param HTTPS on;

.. _`Symfony Proxy Documentation`: https://symfony.com/doc/current/deployment/proxies.html
.. _`Request::isSecure`: https://github.com/symfony/symfony/blob/v5.1.5/src/Symfony/Component/HttpFoundation/Request.php#L1139-L1141
.. _`Request::getScheme`: https://github.com/symfony/symfony/blob/31b6a95fc288617ccfa27aa819d30c0c2201416a/src/Symfony/Component/HttpFoundation/Request.php#L910-L913
