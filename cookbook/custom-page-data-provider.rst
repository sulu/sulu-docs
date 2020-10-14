Register a custom DataProvider to filter pages by their template
================================================================

The ``smart_content`` content type allows for the configuration of a ``provider`` param, which is used for gathering
the items that are passed to the twig template. As described in :doc:`/reference/content-types/smart_content`, Sulu
comes with a few included providers and also allows to implement new ones to load custom entities.

In some cases, it might be useful to register an additional provider for pages that only returns pages that use a
specific template or include a property with a specific value. This is possible by registering a new instance of
the ``PageDataProvider`` included in Sulu that uses a custom ``QueryBuilder`` implementation. To do this, the
following changes are required:

**1. Implement a custom ``QueryBuilder`` class that adds conditions**

.. code-block:: php

    <?php
    // src/SmartContent/ProductPageQueryBuilder.php

    namespace App\SmartContent;

    use Sulu\Component\Content\SmartContent\QueryBuilder;

    class ProductPageQueryBuilder extends QueryBuilder
    {
        protected function buildWhere($webspaceKey, $locale)
        {
            // reuse existing functionality of the default QueryBuilder implementation
            $sql2Where = explode(' AND ', parent::buildWhere($webspaceKey, $locale));

            // add custom condition to check the template of the page to the query
            $sql2Where[] = "(page.[i18n:" . $locale . "-template] = 'product')";

            // optional: add custom condition to check the value of a specific property of the page to the query
            // $sql2Where[] = "(page.[i18n:" . $locale . "-propertyName] = 'propertyValue')";

            return implode(' AND ', $sql2Where);
        }
    }

**2. Register ``PageDataProvider` service that uses custom ``QueryBuilder`` implementation**

.. code-block:: yaml

    # config/services.yaml
    services:
        app.smart_content.product_page_query_builder:
            class: App\SmartContent\ProductPageQueryBuilder
            arguments:
                - '@sulu.content.structure_manager'
                - '@sulu_page.extension.manager'
                - '@sulu.phpcr.session'
                - '%sulu.content.language.namespace%'

        app.smart_content.data_provider.news:
            class: Sulu\Component\Content\SmartContent\PageDataProvider
            arguments:
                - '@app.smart_content.product_page_query_builder'
                - '@sulu.content.query_executor'
                - '@sulu_document_manager.document_manager'
                - '@sulu_page.smart_content.data_provider.content.proxy_factory'
                - '@sulu_document_manager.default_session'
                - '@sulu_page.reference_store.content'
                - '%sulu_document_manager.show_drafts%'
            tags:
                - { name: 'sulu.smart_content.data_provider', alias: 'product_pages'}

**3. Use registered ``product_pages`` DataProvider in the template**

.. code-block:: xml

    <property name="productPages" type="smart_content">
        <meta>
            <title lang="en">Product Pages</title>
        </meta>

        <params>
            <param name="provider" value="product_pages"/>
        </params>
    </property>
