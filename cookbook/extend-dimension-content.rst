Extending DimensionContent
==========================

In Sulu 3.0, content entities such as pages, articles, and snippets are split into two parts:

* the content-rich entity itself, for example ``Page``, ``Article``, or ``Snippet``
* one or more ``DimensionContent`` records, which hold locale-, stage- and version-aware content

That distinction is the most important design decision when you want to add custom fields.

Use the content-rich entity itself when the value is global for the resource and should not vary by locale, stage or
version.
Typical examples are structural values such as the page tree position or the page webspace.

Use the ``DimensionContent`` entity when the value belongs to the editable content lifecycle and should participate in
draft/live handling, locale copies, workflow transitions, previews, or version restores.
That is the right place for fields such as editorial metadata, custom tabs on the edit form, or queryable content
properties.

What Is Mandatory
-----------------

For a real Sulu 3.0 ``DimensionContent`` extension, the following pieces are typically mandatory:

1. Replace the content-rich entity and the dimension content model.
2. Make the content-rich entity create your custom dimension content class.
3. Add the database columns or relation tables.
4. Add a ``ContentDataMapper`` for writing custom fields.
5. Add a ``ContentMerger`` for fields that participate in merged content.

Add a ``ContentNormalizer`` when the serialized API output should differ from the default getter serialization, for
example when you need nested form data, relation IDs, or computed fields.

Replace both models for your content type
-----------------------------------------

The content-rich entity and the ``DimensionContent`` model are configured independently. Replace both, not just the
dimension content class.

For articles:

.. code-block:: yaml

    # config/packages/sulu_article.yaml
    sulu_article:
        objects:
            article:
                model: App\Entity\Article
            article_content:
                model: App\Entity\ArticleDimensionContent

For pages:

.. code-block:: yaml

    # config/packages/sulu_page.yaml
    sulu_page:
        objects:
            page:
                model: App\Entity\Page
            page_content:
                model: App\Entity\PageDimensionContent

For snippets:

.. code-block:: yaml

    # config/packages/sulu_snippet.yaml
    sulu_snippet:
        objects:
            snippet:
                model: App\Entity\Snippet
            snippet_content:
                model: App\Entity\SnippetDimensionContent

The content-rich entity must return your custom dimension content class from ``createDimensionContent()``:

.. code-block:: php

    <?php

    declare(strict_types=1);

    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Sulu\Article\Domain\Model\Article as SuluArticle;
    use Sulu\Content\Domain\Model\DimensionContentInterface;

    #[ORM\Entity]
    #[ORM\Table(name: 'ar_articles')]
    class Article extends SuluArticle
    {
        public function createDimensionContent(): DimensionContentInterface
        {
            return new ArticleDimensionContent($this);
        }
    }

The same pattern applies to pages and snippets. This is mandatory because Sulu creates missing localized and
unlocalized dimension contents through the content-rich entity.

Create the custom DimensionContent entity
-----------------------------------------

Extend the Sulu base class and map your new fields there. The following example uses an article, but the same pattern
works for pages and snippets:

.. code-block:: php

    <?php

    declare(strict_types=1);

    namespace App\Entity;

    use Doctrine\DBAL\Types\Types;
    use Doctrine\ORM\Mapping as ORM;
    use Sulu\Article\Domain\Model\ArticleDimensionContent as SuluArticleDimensionContent;
    use Sulu\Article\Domain\Model\ArticleInterface;

    #[ORM\Entity]
    #[ORM\Table(name: 'ar_article_dimension_contents')]
    class ArticleDimensionContent extends SuluArticleDimensionContent
    {
        #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
        private int $status = 0;

        /**
         * @var array<string, mixed>
         */
        #[ORM\Column(type: Types::JSON)]
        private array $tabData = [];

        public function __construct(ArticleInterface $article)
        {
            parent::__construct($article);
        }

        public function getStatus(): int
        {
            return $this->status;
        }

        public function setStatus(int $status): static
        {
            $this->status = $status;

            return $this;
        }

        /**
         * @return array<string, mixed>
         */
        public function getTabData(): array
        {
            return $this->tabData;
        }

        /**
         * @param array<string, mixed> $tabData
         */
        public function setTabData(array $tabData): static
        {
            $this->tabData = $tabData;

            return $this;
        }
    }

When you replace Sulu's entity in-place, keep the original table name. For the built-in models these are:

* ``pa_pages`` and ``pa_page_dimension_contents``
* ``ar_articles`` and ``ar_article_dimension_contents``
* ``sn_snippets`` and ``sn_snippet_dimension_contents``

Writing custom fields
---------------------

Sulu only persists template data automatically. Everything you add as a dedicated property on the entity needs a
custom ``ContentDataMapper``.

The mapper receives both the unlocalized and the localized dimension content. That is where you decide whether a field
is shared across locales or locale-specific:

* write to ``$localizedDimensionContent`` if the value may differ per locale
* write to ``$unlocalizedDimensionContent`` if the value is shared across locales but should still follow the content
  lifecycle

.. code-block:: php

    <?php

    declare(strict_types=1);

    namespace App\Content\DataMapper;

    use App\Entity\ArticleDimensionContent;
    use Sulu\Content\Application\ContentDataMapper\DataMapper\DataMapperInterface;
    use Sulu\Content\Domain\Model\DimensionContentInterface;

    final class ArticleCustomFieldsDataMapper implements DataMapperInterface
    {
        public function map(
            DimensionContentInterface $unlocalizedDimensionContent,
            DimensionContentInterface $localizedDimensionContent,
            array $data,
        ): void {
            if (!$localizedDimensionContent instanceof ArticleDimensionContent) {
                return;
            }

            if (\array_key_exists('metadata', $data)
                && \is_array($data['metadata'])
                && \array_key_exists('status', $data['metadata'])
            ) {
                $localizedDimensionContent->setStatus((int) $data['metadata']['status']);
            }

            if (\array_key_exists('tabData', $data) && \is_array($data['tabData'])) {
                $localizedDimensionContent->setTabData($data['tabData']);
            }
        }
    }

Reading custom fields
---------------------

Sulu first serializes getters with Symfony's ``GetSetMethodNormalizer`` and only then applies tagged content
normalizers.

That means a custom ``ContentNormalizer`` is only required when the output should differ from the default getter
serialization. If your entity getters already return exactly the API shape you want, you can omit it.

This is also important for copy and restore operations, because Sulu copies content by normalizing the source
dimension content and persisting that normalized data again.

.. code-block:: php

    <?php

    declare(strict_types=1);

    namespace App\Content\Normalizer;

    use App\Entity\ArticleDimensionContent;
    use Sulu\Content\Application\ContentNormalizer\Normalizer\NormalizerInterface;

    final class ArticleCustomFieldsNormalizer implements NormalizerInterface
    {
        public function enhance(object $object, array $normalizedData): array
        {
            if (!$object instanceof ArticleDimensionContent) {
                return $normalizedData;
            }

            $normalizedData['metadata'] = [
                'status' => $object->getStatus(),
            ];

            return $normalizedData;
        }

        public function getIgnoredAttributes(object $object): array
        {
            if (!$object instanceof ArticleDimensionContent) {
                return [];
            }

            // Ignore the raw getter output because we expose the value under metadata/status instead.
            return ['status'];
        }
    }

Merging custom fields
---------------------

Add a ``ContentMerger`` for custom fields on the entity.

Mergers are used whenever Sulu creates a resolved dimension content from multiple sources, for example while
aggregating localized and unlocalized content, while applying workflow transitions, or while copying content between
locales or stages.

.. code-block:: php

    <?php

    declare(strict_types=1);

    namespace App\Content\Merger;

    use App\Entity\ArticleDimensionContent;
    use Sulu\Content\Application\ContentMerger\Merger\MergerInterface;

    final class ArticleCustomFieldsMerger implements MergerInterface
    {
        public function merge(object $targetObject, object $sourceObject): void
        {
            if (!$targetObject instanceof ArticleDimensionContent) {
                return;
            }

            if (!$sourceObject instanceof ArticleDimensionContent) {
                return;
            }

            $targetObject->setStatus($sourceObject->getStatus());
            $targetObject->setTabData(\array_merge(
                $targetObject->getTabData(),
                $sourceObject->getTabData(),
            ));
        }
    }

Register the services
---------------------

Register the three services with the Sulu content tags:

.. code-block:: yaml

    services:
        App\Content\DataMapper\ArticleCustomFieldsDataMapper:
            tags:
                - { name: 'sulu_content.data_mapper', priority: 64 }

        App\Content\Normalizer\ArticleCustomFieldsNormalizer:
            tags:
                - { name: 'sulu_content.normalizer', priority: 20 }

        App\Content\Merger\ArticleCustomFieldsMerger:
            tags:
                - { name: 'sulu_content.merger', priority: 20 }

The exact priority depends on which built-in mappers or normalizers should run before or after your custom logic. The
shown priorities are examples, not fixed rules.

Choosing the right storage pattern
----------------------------------

There is no single best way to store extra data. The right choice depends on lifecycle, query needs, and structure.

Put it on the content-rich entity
.................................

Use the content-rich entity when the value is global for the resource and should not depend on locale, stage, or
version.

Good examples:

* page tree information
* webspace assignment
* resource identifiers

For editor-managed fields this is the exception, not the rule.

Put it on ``DimensionContent`` as scalar columns
................................................

Use direct columns when the field belongs to the content lifecycle and is easy to reason about as a single value.

This is usually the best choice for:

* booleans, integers, small strings, dates
* values used in repository filters or Doctrine queries
* values used in list builders, sorting, or database indexes

This pattern is a good fit for fields such as ``status``, ``priority``, import identifiers, or other values that need
to be filtered or sorted efficiently.

Put it on ``DimensionContent`` as a relation
............................................

Use a relation when the value has its own identity or lifecycle, or when referential integrity matters.

This is usually the right choice for:

* many-to-many selections
* reusable value sets managed elsewhere in the system
* data that should have its own table, constraints, permissions, or admin UI

Examples from existing Sulu 3.0 code are:

* ``PageDimensionContent.navigationContexts``
* ``ArticleDimensionContent.additionalWebspaces``
* your own many-to-many or one-to-many relations for editor-managed reference data

Put it in a JSON column
.......................

Use a JSON column when several values form one conceptual group, but you do not need to query them efficiently at the
database level.

This is a good fit for:

* grouped settings shown in one custom tab
* low-query metadata
* nested structures that are convenient in the admin API

This pattern works well for grouped tab-specific JSON fields. Those fields are edited as nested form groups, but they
are not used as first-class database filters.

Do not hide query-critical fields in JSON. If you need to filter, sort, or index them regularly, keep dedicated
columns instead.
