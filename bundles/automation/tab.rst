Integration into custom module
==============================

The AutomationBundle provides a simple way to integrate the ``automation-tab``
into your own custom module.

1. Handler
----------

Be sure the application provides some handlers for the custom entity-class.
This can be ensured by including custom handlers and supporting it via the
interface (see :doc:`handler`).

2. NavigationProvider
---------------------

Create a new NavigationProvider and register it in the ``services.xml`` file
(see :doc:`../../cookbook/using-tab-navigation`).

.. code-block:: php

    <?php

    namespace AppBundle\Admin;

    use Sulu\Bundle\AdminBundle\Navigation\ContentNavigationItem;
    use Sulu\Bundle\AdminBundle\Navigation\ContentNavigationProviderInterface;
    use Sulu\Bundle\ArticleBundle\Document\ArticleDocument;
    use Sulu\Bundle\ContentBundle\Document\PageDocument;

    class ArticleAutomationContentNavigationProvider implements ContentNavigationProviderInterface
    {
        public function getNavigationItems(array $options = [])
        {
            $automation = new ContentNavigationItem('sulu_automation.automation');
            $automation->setId('tab-automation');
            $automation->setAction('automation');
            $automation->setDisplay(['edit']);

            // This component is already implemented in the automation-bundle.
            $automation->setComponent('automation-tab@suluautomation');
            // The entityClass option will customize the tab for your entity.
            $automation->setComponentOptions(['entityClass' => NewsItem::class]);

            return [$automation];
        }
    }

.. note::

    If you integrate this into a public bundle you should check the existence
    of the AutomationBundle in your extension and omit this definition if it
    is not registered.

3. Use it
---------

After clearing the cache you can see the newly created tab in your
custom-module and make use of it.
