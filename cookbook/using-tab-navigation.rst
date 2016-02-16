Using tab navigation
====================

It is very easy to build your own or to extend already existing tab navigations
in Sulu. The general process of displaying such a tab navigation in the
administration interface of Sulu covers the following steps:

1. Your JavaScript component sends a request to 
   ``/admin/content-navigations?alias=acme``, and might add more options as
   query parameters.
2. The server responds to this request based on so called
   ``ContentNavigationProviders``, which are registered to listen to a certain
   alias, and the passed query parameters.
3. The content is returned and Sulu's JavaScript Tab component renders the
   delivered information for you as tabs.

This article will describe how this can be achieved in a few simple steps.

Create a content navigation provider
------------------------------------

If you want to create your own tab navigation, you have to build a provider for
it first. A provider is just a simple service implementing the
``ContentNavigationProviderInterface`` containing a function named
``getNavigationItems``. The task of this function is to return an array of
``ContentNavigationItems``, the following lines show an example of this:

.. code-block:: php

    <?php
    
    namespace Acme\Bundle\Example\Admin;

    use Sulu\Bundle\AdminBundle\Navigation\ContentNavigationProviderInterface;
    use Sulu\Bundle\AdminBundle\Navigation\ContentNavigationItem;

    class AcmeContentNavigationProvider implements ContentNavigationProviderInterface
    {
        public function getNavigationItems(array $options = array())
        {
            $item = new ContentNavigationItem('Item');
            $item->setAction('item');
            $item->setDisplay(array('edit'));
            $item->setComponent('item-tab@acmeexample');
            $item->setComponentOptions(array());

            return array($item);
        }
    }

The ``getNavigationItems`` function takes an array with options. These options
are all the query parameters that were passed via the HTTP request. You can
base certain decisions on these options like if some navigation items should be
created at all, or you can pass these options to the JavaScript components
which will be started when selecting a specific tab.

.. note::

    Since this class will be registered as a service, you can inject any other
    service you want to help you decide which ``ContentNavigationItems`` you
    want to create. It is quite common to use ``SecurityChecker`` to check for
    certain privileges before creating an item.

The ``ContentNavigationItem`` takes several arguments. The action will be
appended to the URL in the administration interface. Display defines in which
cases the tab is appearing (available options are ``new`` for forms creating a
new entry and ``edit`` for forms editing already existing entries). Component
sets the name of the aura component which should be started and
``ComponentOptions`` are the options which will be passed to this component.

Register the content provider as a service
------------------------------------------

Afterwards you have to register your content navigation provider as a service
in the dependency injection container. This is quite basic, but you have to add
a tag named ``sulu.admin.content_navigation`` together with an alias, which
will be used by a service to find all content navigation providers for the
request sent from the javascript component.

.. note::

    You can also register multiple services with the same alias. The items will
    then be merged, this way it is very easy to extend existing content
    navigations.

.. code-block:: xml

    <?xml version="1.0" encoding="UTF-8" ?>
    <container xmlns="http://symfony.com/schema/dic/services"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
        <service id="acme_example.content_navigation" class="Acme\Bundle\Example\Admin\AcmeContentNavigation">
            <tag name="sulu_admin.content_navigation" alias="acme"/>
        </service>
    </container>
