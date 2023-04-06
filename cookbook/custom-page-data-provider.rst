Filter pages by a property using a custom SmartContent DataProvider
===================================================================

The ``smart_content`` content type allows for the configuration of a ``provider`` param, which is used for gathering
the items that are passed to the twig template. As described in :doc:`/reference/content-types/smart_content`, Sulu
comes with a few included providers and also allows to implement new ones to load custom entities.

In some cases, it might be useful to register an additional provider, which only returns pages including a property
with a specific value. This is possible by registering a new instance of the ``PageDataProvider`` included in Sulu
that uses a custom implementation of the ``QueryBuilder``. In the following example a custom ``AuthorPageDataProvider``
is implemented, which filters the pages for a specific author:

**1. Implement a custom** ``QueryBuilder`` **class that adds conditions**

.. code-block:: php

    <?php
    // src/SmartContent/AuthorPageQueryBuilder.php

    namespace App\SmartContent;

    use Sulu\Bundle\ContactBundle\Entity\ContactRepositoryInterface;
    use Sulu\Component\Content\Compat\StructureManagerInterface;
    use Sulu\Component\Content\Extension\ExtensionManagerInterface;
    use Sulu\Component\Content\SmartContent\QueryBuilder;
    use Sulu\Component\PHPCR\SessionManager\SessionManagerInterface;

    class AuthorPageQueryBuilder extends QueryBuilder
    {
        public function __construct(
            StructureManagerInterface $structureManager,
            ExtensionManagerInterface $extensionManager,
            SessionManagerInterface $sessionManager,
            $languageNamespace,
            private ContactRepositoryInterface $contactRepository
        )
        {
            parent::__construct($structureManager, $extensionManager, $sessionManager, $languageNamespace);
        }

        protected function buildWhere($webspaceKey, $locale)
        {
            // reuse existing functionality of the default QueryBuilder implementation
            $sql2Where = explode(' AND ', parent::buildWhere($webspaceKey, $locale));

            // Find authors by firstName and lastName
            $authors = $this->contactRepository->findBy(['firstName' => 'Adam', 'lastName' => 'Ministrator']);
            // Get the first author
            $author = reset($authors);

            // add custom condition to check the author of the page to the query
            $sql2Where[] = "(page.[i18n:" . $locale . "-author] = " . $author->getId() . ")";

            // optional: add custom condition to check the value of a specific property of the page to the query
            // $sql2Where[] = "(page.[i18n:" . $locale . "-propertyName] = 'propertyValue')";

            return implode(' AND ', $sql2Where);
        }
    }

**2. Register** ``PageDataProvider`` **service that uses custom** ``QueryBuilder`` **implementation**

.. code-block:: yaml

    # config/services.yaml
    services:
        app.smart_content.author_page_query_builder:
            class: App\SmartContent\AuthorPageQueryBuilder
            arguments:
                - '@sulu.content.structure_manager'
                - '@sulu_page.extension.manager'
                - '@sulu.phpcr.session'
                - '%sulu.content.language.namespace%'
                - '@sulu.repository.contact'

        app.smart_content.data_provider.author_pages:
            class: Sulu\Component\Content\SmartContent\PageDataProvider
            arguments:
                - '@app.smart_content.author_page_query_builder'
                - '@sulu.content.query_executor'
                - '@sulu_document_manager.document_manager'
                - '@sulu_page.smart_content.data_provider.content.proxy_factory'
                - '@sulu_document_manager.default_session'
                - '@sulu_page.reference_store.content'
                - '%sulu_document_manager.show_drafts%'
                - '%sulu_security.permissions%'
                - '@=container.hasParameter(\'sulu_audience_targeting.enabled\')'
                - '@sulu_admin.form_metadata_provider'
                - '@security.token_storage'
            tags:
                - { name: 'sulu.smart_content.data_provider', alias: 'author_pages' }

**3. Use registered** ``author_pages`` **DataProvider in the template**

.. code-block:: xml

    <property name="authorPages" type="smart_content">
        <meta>
            <title lang="en">Author Pages</title>
        </meta>

        <params>
            <param name="provider" value="author_pages"/>
        </params>
    </property>

Adding custom sorting
---------------------

You can implement custom sorting criteria by creating a new ``DataProvider`` class that extends from the ``PageDataProvider`` class and using it instead.
That way, you can sort by a property that isn't provided by the ``PageDataProvider``. 
When a lot of business logic it is probably better to create a custom entity instead.

**1. Create a custom** ``DataProvider`` **class that overrides the** ``PageDataProvider`` **configuration.**

.. code-block:: php

    <?php
    // src/SmartContent/CustomPageDataProvider.php

    namespace App\SmartContent;

    use Sulu\Bundle\PageBundle\Admin\PageAdmin;
    use Sulu\Component\Content\SmartContent\PageDataProvider;
    use Sulu\Component\SmartContent\Configuration\ProviderConfiguration;
    use Sulu\Component\SmartContent\Configuration\ProviderConfigurationInterface;

    class EventPageDataProvider extends PageDataProvider
    {
        public function getConfiguration(): ProviderConfigurationInterface
        {
            return $this->initConfiguration();
        }

        private function initConfiguration(): ProviderConfigurationInterface
        {
            /** @var ProviderConfiguration $configuration */
            $configuration = parent::getConfiguration();

            $configuration->setTags(false);
            $configuration->setCategories(false);
            $configuration->setLimit(false);
            $configuration->setPaginated(false);
            $configuration->setPresentAs(false);
            $configuration->setAudienceTargeting(false);
            $configuration->setSorting([
                ...$configuration->getSorting(),
                ['column' => 'datetimeFrom', 'title' => 'sulu_admin.datetime_from'],
            ]);
            $configuration->setView(PageAdmin::EDIT_FORM_VIEW);

            return $configuration;
        }
    }


.. list-table::
    :header-rows: 1

    * - Method
      - Parameters
      - Description
    * - `setTags`
      - bool
      - Enable tags to be selecteable for smart_content.
    * - `setTags`
      - bool
      - Enable categories.
    * - `setLimit`
      - bool
      - Enable limit.
    * - `setPaginated`
      - bool
      - Enable pagination.
    * - `setPresentAs`
      - bool
      - Enable present as.
    * - `setAudienceTargeting`
      - bool
      - Enable audience targeting.
    * - `setSorting`
      - collection
      - Accepts a nested array of two-dimensional arrays. Each two-dimensional array should include a `column` key with the property to sort by, and a `title` key with the label to display in the sorting dropdown menu.
    * - `setView`
      - `array<string, string> $resultToView`
      - Defines where the deep link when clicking on a smart content item should navigate to.

**2. Use the custom** ``DataProvider`` **in the** ``services.yaml`` **.**

.. code-block:: yaml

        app.smart_content.data_provider.author_pages:
            class: App\SmartContent\EventPageDataProvider


All other parameters remain the same as you would register a ``PageDataProvider`` service that uses a custom ``QueryBuilder`` implementation.
