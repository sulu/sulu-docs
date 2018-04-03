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

For the `Symfony cache`_ the audience targeting cache listener needs to be added.
This is possible by adding the constructor to `WebsiteCache` in `app/WebsiteCache.php`:

.. code-block:: php

    public function __construct(HttpKernelInterface $kernel, $cacheDir = null)
    {
        parent::__construct($kernel, $cacheDir);

        $this->addSubscriber(new AudienceTargetingCacheListener());
    }

If you want to use the more powerful `Varnish`_ instead, you have to install it
on your machine and configure it using a VCL.

The following will add full caching support including audience targeting for Sulu:

.. code-block:: varnish4

    # /etc/varnish/default.vcl
    vcl 4.0;

    include "<PATH-TO-SULU-MINIMAL>/vendor/sulu/sulu/src/Sulu/Bundle/HttpCacheBundle/Resources/varnish/sulu.vcl";
    include "<PATH-TO-SULU-MINIMAL>/vendor/sulu/sulu/src/Sulu/Bundle/AudienceTargetingBundle/Resources/varnish/sulu.vcl";

    acl invalidators {
        "localhost";
    }

    backend default {
        .host = "127.0.0.1";
        .port = "8090";
    }

    sub vcl_recv {
        call sulu_recv;
        call sulu_audience_targeting_recv;

        # Force the lookup, the backend must tell not to cache or vary on all
        # headers that are used to build the hash.
        return (hash);
    }

    sub vcl_backend_response {
        call sulu_backend_response;
    }

    sub vcl_deliver {
        call sulu_audience_targeting_deliver;
        call sulu_deliver;
    }

Finally you have to make sure that the bundle is correctly recognized by
Symfony. This includes the following steps:

* Clear the Symfony cache with the `cache:clear` command or manually
  deleting the cache folder
* Rebuild the translations with the `sulu:translate:export` command
* Install the new assets using the `assets:install --symlink`
* Make sure that the users the feature should be enabled for have the correct
  permissions

Manually set target group
-------------------------

Sulu will try to determine a matching target group based on the rules the
content manager defines. But it is also possible to set a target group
manually. That might be useful if you want to divide visitors into separate
target groups based on some behavior, e.g. filling out a form, starting a
download, etc.

Therefore we have introduced the `TargetGroupStore`. You can simply call its
`updateTargetGroupId` method and Sulu will do the rest for you. This would like
this in an action of a Controller:

.. code-block:: php

    <?php
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;

    class DefaultController extends Controller {
        public function indexAction() {
            // determine the desired target group based on form values, etc.
            $targetGroupId = 0;
            $this->get('sulu_audience_targeting.target_group_store')
                ->updateTargetGroupId($targetGroupId);
        }
    }

.. note::

    The target group that will be set manually should have quite a high
    priority, otherwise another higher prioritized target group might override
    that based on its defined rule.

Create custom rules
-------------------

The cool thing about target groups are the rules you can define on them, which
will automatically evaluated by Sulu. There are a few rules built-in, like a
referrer rule, browser rule or a page rule. However, you might still have a
very specific use case, which requires to implement your own custom rule.

Luckily this possibility is also built-in into Sulu. First of all you have to
write your own implementation of the `RuleInterface`:

.. code-block:: php

    <?php

    namespace Acme\Bundle\Rule;

    use Sulu\Bundle\AudienceTargetingBundle\Rule\RuleInterface;

    class ExampleRule implements RuleInterface {
        public function evaluate(array $options)
        {
            // return true if the rule is matching, otherwise false
        }

        public function getName()
        {
            // return the name of the rule
        }

        public function getType()
        {
            // return an implementation of the RuleTypeInterface
        }
    }

The interface consists of three different methods, lets have a closer look at
each one of them:

The easiest one is `getName`, whatever you return here will be shown in the
rules dropdown.

The `getType` method returns how the rule is displayed in the admin. This is
what the content manager will be facing, if this rule was chosen. There are a
few possibilities, represented by classes implementing the `RuleTypeInterface`.
They usually take some kind of name as constructor parameter, which will be
used as key when storing this information in a JSON field in the database. The
content of this JSON field is what will be passed to the `$options` argument of
the `evaluate` method later. Until now there are implementations for `Text`,
`Select`, `KeyValue` and for a `InternalLink`.

The `evaluate` method will be called for every appearance of the rule in all
the target groups, until one of the target groups matches. The `$options`
argument will be filled with the information from the conditions the content
manager has configured as already mentioned above. Based on this information
you have to define if the current request can be evaluated to `true`.

.. note::

    In most cases you need to inject other services to your rule, in order to
    be able to evaluate them in a sensible way. Quite often this is the
    `RequestStack`, which allows you to get the current `Request` object and
    allows you to evaluate certain values against the request.

Finally your implementation has to be registered as service using the
`sulu.audience_target_rule` tag:

.. code-block:: xml

    <?xml version="1.0" ?>
    <container xmlns="http://symfony.com/schema/dic/services"
               xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
               xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
        <services>
            <service id="acme_bundle.rule"
                     class="Acme\Bundle\Rule\ExampleRule">
                <!-- inject whatever services you need -->
                <tag name="sulu.audience_target_rule" alias="acme"/>
            </service>
        </services>
    </container>

.. note::

    Mind that the `alias` of the tag has to be unique.

.. _Symfony Cache: http://symfony.com/doc/current/http_cache.html
.. _Varnish: https://www.varnish-cache.org/
.. _varnish-modules: https://github.com/varnish/varnish-modules
