HTTPS
=====

Sulu generates URLs in the same way as Symfony does..
If the requested protocol is ``https`` it will use automatically ``https`` to generate its URLs.

If this does not work propertly, your setup probably includes some kind of proxy, e.g. a load balancer or a HTTP cache like Varnish.
In that case you should add the IP address of your trusted proxy as explained in the `Symfony Proxy Documentation`_.

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
