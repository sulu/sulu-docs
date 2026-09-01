Filter pages by a property using a custom Page SmartContent Provider
======================================================================

The ``smart_content`` property type supports a ``provider`` param that controls which items are
loaded. In some cases it is useful to register an additional provider that only returns pages
matching a specific criterion. The following example creates an ``AuthorPagesSmartContentProvider``
that restricts results to pages written by a specific author.

.. note::

    The same pattern applies to articles — extend
    ``Sulu\Article\Infrastructure\Sulu\Content\ArticleSmartContentProvider`` and use
    ``parent: sulu_article.article_smart_content_provider`` in the service definition.

Example
-------

Create the class in ``src/SmartContent/``:

.. code-block:: php

    <?php

    declare(strict_types=1);

    namespace App\SmartContent;

    use Doctrine\ORM\EntityManagerInterface;
    use Doctrine\ORM\QueryBuilder;
    use Sulu\Bundle\AdminBundle\Metadata\MetadataProviderInterface;
    use Sulu\Bundle\AdminBundle\SmartContent\Configuration\BuilderInterface;
    use Sulu\Bundle\AdminBundle\SmartContent\SmartContentQueryEnhancer;
    use Sulu\Bundle\SecurityBundle\AccessControl\AccessControlQueryEnhancer;
    use Sulu\Component\Webspace\Manager\WebspaceManagerInterface;
    use Sulu\Content\Infrastructure\Doctrine\DimensionContentQueryEnhancer;
    use Sulu\Page\Infrastructure\Sulu\Content\PageSmartContentProvider;
    use Symfony\Bundle\SecurityBundle\Security;
    use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

    readonly class AuthorPagesSmartContentProvider extends PageSmartContentProvider
    {
        public function __construct(
            DimensionContentQueryEnhancer $dimensionContentQueryEnhancer,
            MetadataProviderInterface $formMetadataProvider,
            SmartContentQueryEnhancer $smartContentQueryEnhancer,
            ?TokenStorageInterface $tokenStorage,
            EntityManagerInterface $entityManager,
            array $bundles,
            WebspaceManagerInterface $webspaceManager,
            AccessControlQueryEnhancer $accessControlQueryEnhancer,
            ?Security $security,
            ?array $permissions,
            private int $authorContactId,
        ) {
            parent::__construct(
                $dimensionContentQueryEnhancer,
                $formMetadataProvider,
                $smartContentQueryEnhancer,
                $tokenStorage,
                $entityManager,
                $bundles,
                $webspaceManager,
                $accessControlQueryEnhancer,
                $security,
                $permissions,
            );
        }

        public function getType(): string
        {
            return 'author_pages';
        }

        protected function getConfigurationBuilder(): BuilderInterface
        {
            return parent::getConfigurationBuilder()
                ->enableSorting([
                    ['column' => 'authored', 'title' => 'sulu_admin.authored'],
                    ['column' => 'title',    'title' => 'sulu_admin.title'],
                ]);
        }

        protected function addInternalFilters(QueryBuilder $queryBuilder, array $filters, string $alias): void
        {
            parent::addInternalFilters($queryBuilder, $filters, $alias);

            $queryBuilder
                ->andWhere('IDENTITY(filterDimensionContent.author) = :authorContactId')
                ->setParameter('authorContactId', $this->authorContactId);
        }

        protected function addInternalSortBys(QueryBuilder $queryBuilder, array $sortBys, string $alias): void
        {
            parent::addInternalSortBys($queryBuilder, $sortBys, $alias);

            $queryBuilder->addOrderBy('filterDimensionContent.authored', 'desc');
        }
    }

- The constructor must be redeclared when adding new dependencies (here: ``$authorContactId``). All
  parent arguments are forwarded via ``parent::__construct()``. Symfony's ``parent:`` service key
  handles the parent arguments automatically; only the extra argument needs to be configured.
- ``getConfigurationBuilder()`` — call ``parent::getConfigurationBuilder()`` to keep the built-in
  features (datasource, tags, categories, …), then override only what you need.
- ``addInternalFilters()`` — the ``parent::`` call is **required**; it applies the built-in
  ``dataSource`` and website tag/category filters. Add custom ``WHERE`` conditions after it.
- ``addInternalSortBys()`` — called by sulu **after** ``addInternalFilters()``, receiving
  the user-selected sort columns via ``$sortBys``. Call ``parent::addInternalSortBys()`` to apply
  those, then append any fixed default ordering. Any ``addOrderBy`` added here is automatically
  included in the ``SELECT DISTINCT`` list.

.. note::

    ``filterDimensionContent`` is the alias for the joined ``PageDimensionContent`` entity,
    created by ``DimensionContentQueryEnhancer`` before ``addInternalFilters()`` is called.


Register the service in ``config/services.yaml``. The ``parent:`` key inherits all constructor
arguments from the built-in provider. Only the additional ``$authorContactId`` scalar must be
configured explicitly replace ``42`` with the contact ID of the desired author:

.. code-block:: yaml

    services:
        App\SmartContent\AuthorPagesSmartContentProvider:
            parent: sulu_page.page_smart_content_provider
            arguments:
                $authorContactId: 42
            tags:
                - { name: sulu_content.smart_content_provider, type: author_pages }

Reference the provider in a page template:

.. code-block:: xml

    <property name="pages" type="smart_content">
        <meta>
            <title lang="en">Author Pages</title>
        </meta>
        <params>
            <param name="provider" value="author_pages"/>
        </params>
    </property>
