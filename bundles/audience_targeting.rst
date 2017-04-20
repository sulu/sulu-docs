AudienceTargetingBundle
=======================

If you want to display different content on the same URL based on some
characteristics of the user, you can use the AudienceTargetingBundle for that.

It allows the content manager to define the audience target groups on his own.
Therefore it also defines some rules which will be evaluated to a target group.

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

Add the routes for the administration interface:

.. code-block:: yaml

    # ...
    sulu_audience_targeting:
        resource: "@SuluAudienceTargetingBundle/Resources/config/routing.xml"
        prefix: /admin/target-groups

    sulu_audience_targeting_api:
        resource: "@SuluAudienceTargetingBundle/Resources/config/routing_api.xml"
        prefix: /admin/api

And the routes for the website:

.. code-block:: yaml

    # ...
    sulu_audience_targeting:
        resource: "@SuluAudienceTargetingBundle/Resources/config/routing_website.xml"

Finally the cache in the front controller for the website at `web/website.php`
has to be told to make use of the user context feature. You do that by adding
`true` as a second parameter:

.. code-block:: php

    <?php
    $kernel = new WebsiteCache($kernel, true);
