AudienceTargetingBundle
=======================

If you want to display different content on the same URL based on some
characteristics of the visitor, you can use the AudienceTargetingBundle for
that.

It allows the content manager to define the audience target groups on his own.
Each target group has one or multiple rules, which are used to determine the
target group of the visitor.

Installation
------------

Enable the bundle in `app/AbstractKernel.php`:

.. code-block:: php

    <?php
    abstract class AbstractKernel extends SuluKernel
    {
        // ...

        public function reigsterBundles()
        {
            $bundles = [
                // ...
                new Sulu\Bundle\AudienceTargetingBundle\SuluAudienceTargetingBundle(),
            ];

            return $bundles;
        }
    }

Add the routes for the administration interface to the routing configuration
file (`app/config/admin/routing.yml`):

.. code-block:: yaml

    # ...
    sulu_audience_targeting:
        resource: "@SuluAudienceTargetingBundle/Resources/config/routing.xml"
        prefix: /admin/target-groups

    sulu_audience_targeting_api:
        resource: "@SuluAudienceTargetingBundle/Resources/config/routing_api.xml"
        prefix: /admin/api

And the routes for the website in the corresponding configuration file
(`app/config/website/routing.yml`):

.. code-block:: yaml

    # ...
    sulu_audience_targeting:
        resource: "@SuluAudienceTargetingBundle/Resources/config/routing_website.xml"

Finally the cache has to be correctly configured. You have the choice between
the Symfony Cache and Varnish.

For the `Symfony cache`_ the front controller for the website at
`web/website.php` has to be told to make use of the user context feature. You
do that by uncommenting the line with the `WebsiteCache` and adding `true` as a
second parameter:

.. code-block:: php

    <?php
    $kernel = new WebsiteCache($kernel, true);

If you want to use the more powerful `Varnish`_ instead, you have to install it
on your machine and configure it using a VCL. You can use the following
example for that:

.. code-block:: c

    vcl 4.0;

    # Default backend definition. Set this to point to your content server.
    backend default {
        .host = "127.0.0.1";
        .port = "8001";
    }

    sub vcl_recv {
        if (req.http.Cookie ~ "user-context") {
            set req.http.X-User-Context = regsub(req.http.Cookie, ".*user-context=([^;]+).*", "\1");
        } elseif (req.restarts == 0) {
            set req.http.X-Sulu-Original-Url = req.url;
            set req.url = "/_user_context";
        } elseif (req.restarts > 0) {
            set req.url = req.http.X-Sulu-Original-Url;
            unset req.http.X-Sulu-Original-Url;
        }

        unset req.http.Cookie;
    }

    sub vcl_deliver {
        if (resp.http.X-User-Context) {
            set req.http.X-User-Context = resp.http.X-User-Context;
            set req.http.Set-Cookie = "user-context=" + resp.http.X-User-Context + "; expires=Tue, 19 Jan 2038 03:14:07 GMT; path=/;";

            return (restart);
        }

        if (req.http.Set-Cookie) {
            set resp.http.Set-Cookie = req.http.Set-Cookie;
        }
    }

.. _Symfony Cache: http://symfony.com/doc/current/http_cache.html
.. _Varnish: https://www.varnish-cache.org/
