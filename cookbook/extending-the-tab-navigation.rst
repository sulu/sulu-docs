Extending the tab navigation
============================

.. code-block:: php

    <?php
    
    namespace Acme\Bundle\Example\Admin;

    use Sulu\Bundle\AdminBundle\Navigation\ContentNavigationProviderInterface;
    use Sulu\Bundle\AdminBundle\Navigation\ContentNavigationItem;

    class AcmeContentNavigation implements ContentNavigationProviderInterface
    {
        pubilc function getNavigationItems(array $options = array())
        {
            $item = new ContentNavigationItem('Item');
            $item->setAction('item');
            $item->setDisplay(array('edit'));
            $item->setComponent('item-tab@acmeexample');
            $item->setComponentOptions(array());

            return array($item);
        }
    }

.. code-block:: xml

    <?xml version="1.0" encoding="UTF-8" ?>
    <container xmlns="http://symfony.com/schema/dic/services"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
        <service id="acme_example.content_navigation" class="Acme\Bundle\Example\Admin\AcmeContentNavigation">
            <tag name="sulu.admin.content_navigation" alias="acme"/>
        </service>
    </container>
