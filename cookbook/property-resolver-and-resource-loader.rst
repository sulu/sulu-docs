PropertyResolver and ResourceLoader
====================================

PropertyResolvers and ResourceLoaders are core services in Sulu's content resolution system
that work together to efficiently transform and load content data for the website frontend.
They provide a clean separation of concerns: PropertyResolvers define **what** data should
be loaded and **how** it should be transformed, while ResourceLoaders handle the actual
**batch loading** of resources from the database or external sources.

This architecture enables performance optimizations through batch loading and provides
clear extension points for custom property types. You'll need to create custom
PropertyResolvers and ResourceLoaders when building selection fields for custom entities
or integrating external data sources into Sulu's content system.

The Content Resolution Process
-------------------------------

Understanding how PropertyResolvers and ResourceLoaders collaborate helps you implement
them correctly. The content resolution process follows these steps:

1. **Content Resolution Start**: The ``ContentResolver`` receives a request to resolve
   content for a page or content entity.

2. **Property Resolution**: For each property in the template, the appropriate
   ``PropertyResolver`` is called based on the property type. The resolver transforms
   the raw data (typically IDs) into a ``ContentView`` object containing
   ``ResolvableResource`` placeholders.

3. **Priority Queue**: All ``ResolvableResource`` objects are collected in a priority
   queue. Resources with the same priority and loader key are grouped together for
   batch loading.

4. **Batch Loading**: The ``ResolvableResourceLoader`` processes the queue by priority,
   calling the appropriate ``ResourceLoader`` for each group. ResourceLoaders fetch
   multiple resources in a single database query.

5. **Resource Replacement**: The ``ResolvableResourceReplacer`` replaces all
   ``ResolvableResource`` placeholders with the actual loaded data.

6. **Nested Resolution**: If loaded resources are ContentRichEntities (like pages or
   custom entities), they are recursively resolved, repeating the process at the next
   depth level.

7. **Final Output**: The fully resolved content is returned, ready for rendering in Twig
   templates.

This batch loading approach prevents N+1 query problems and ensures optimal performance
even with complex nested content structures.

How to create a custom PropertyResolver?
-----------------------------------------

A PropertyResolver transforms raw property data into a structured ``ContentView`` object.
You need to create a custom PropertyResolver when implementing selection fields for
custom entities or when building property types that reference external data.

PropertyResolvers must implement the ``PropertyResolverInterface`` which requires two
methods:

* ``resolve(mixed $data, string $locale, array $params = []): ContentView`` - Transforms
  the raw data into a ContentView
* ``getType(): string`` - Returns the property type identifier

1. PropertyResolver Implementation
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Here's a complete example of a PropertyResolver for selecting products:

.. code-block:: php

    <?php

    declare(strict_types=1);

    namespace App\Content\PropertyResolver;

    use App\Entity\Product;
    use App\Content\ResourceLoader\ProductResourceLoader;
    use Sulu\Content\Application\ContentResolver\Value\ContentView;
    use Sulu\Content\Application\PropertyResolver\Resolver\PropertyResolverInterface;

    class ProductSelectionPropertyResolver implements PropertyResolverInterface
    {
        public function resolve(mixed $data, string $locale, array $params = []): ContentView
        {
            // Handle empty or invalid data
            if (!is_array($data)
                || 0 === count($data)
                || !array_is_list($data)
            ) {
                return ContentView::create([], ['ids' => [], ...$params]);
            }

            // Extract product IDs from data
            $ids = $data;

            // Get ResourceLoader key (allow override via params)
            $resourceLoaderKey = $params['resourceLoader'] ?? ProductResourceLoader::getKey();

            // Create ContentView with resolvable resources
            return ContentView::createResolvablesWithReferences(
                ids: $ids,
                resourceLoaderKey: $resourceLoaderKey,
                resourceKey: Product::RESOURCE_KEY,
                view: [
                    'ids' => $ids,
                    ...$params,
                ],
                priority: 150,
                metadata: [
                    'properties' => $params['properties'] ?? null,
                ]
            );
        }

        public static function getType(): string
        {
            return 'product_selection';
        }
    }

**Key Points:**

* **Data Validation**: Always validate input data and return an empty ContentView for
  invalid data
* **ContentView Factory Methods**: Use ``createResolvablesWithReferences()`` for multiple
  resources that should create reference entries. Use ``createResolvable()`` for single
  resources or ``create()`` for simple data that doesn't need loading
* **Priority Values**: Convention is ``-50`` for links, ``0`` for default/simple types,
  ``150`` for content entities, and higher values for special cases
* **Metadata**: Pass metadata to control which properties are resolved for nested content
  entities
* **Resource Key**: The resource key (e.g., ``Product::RESOURCE_KEY``) is used for
  reference tracking and cache invalidation

2. Service Definition
~~~~~~~~~~~~~~~~~~~~~

Register the PropertyResolver as a service with the ``sulu_content.property_resolver`` tag:

.. code-block:: yaml

    # config/services.yaml
    services:
        App\Content\PropertyResolver\ProductSelectionPropertyResolver:
            tags:
                - { name: 'sulu_content.property_resolver' }

.. note::

    With autowiring enabled (the default in Sulu), you don't need to manually register
    these services. Sulu will automatically apply the ``sulu_content.property_resolver``
    tag to all services implementing ``PropertyResolverInterface`` and the
    ``sulu_content.resource_loader`` tag to all services implementing
    ``ResourceLoaderInterface``.

The service tag uses the ``getType()`` method to automatically index the resolver by its
type. When a property with ``type="product_selection"`` is encountered, Sulu will use
this resolver.

3. Template Configuration
~~~~~~~~~~~~~~~~~~~~~~~~~~

Use the custom property type in your page templates:

.. code-block:: xml

    <property name="products" type="product_selection">
        <meta>
            <title lang="en">Products</title>
        </meta>
    </property>

How to create a custom ResourceLoader?
---------------------------------------

A ResourceLoader fetches resources from the database or external sources in batches.
You need to create a custom ResourceLoader for each entity type that can be referenced
by PropertyResolvers.

ResourceLoaders must implement the ``ResourceLoaderInterface`` which requires:

* ``load(array $ids, ?string $locale, array $params = []): array`` - Batch loads
  resources by their IDs
* ``getKey(): string`` - Returns the resource loader identifier

1. ResourceLoader Implementation
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Here's a complete ResourceLoader for products:

.. code-block:: php

    <?php

    declare(strict_types=1);

    namespace App\Content\ResourceLoader;

    use App\Repository\ProductRepository;
    use Sulu\Content\Application\ResourceLoader\Loader\ResourceLoaderInterface;

    class ProductResourceLoader implements ResourceLoaderInterface
    {
        public const RESOURCE_LOADER_KEY = 'products';

        public function __construct(
            private ProductRepository $productRepository,
        ) {
        }

        public function load(array $ids, ?string $locale, array $params = []): array
        {
            // Build filter criteria
            $filters = [
                'ids' => $ids,
                'locale' => $locale,
                'published' => true,
            ];

            // Allow params to override filters
            if (isset($params['filters']) && is_array($params['filters'])) {
                $filters = array_merge($filters, $params['filters']);
            }

            // Load products from repository
            $products = $this->productRepository->findByFilters($filters);

            // Map results by ID (required by the interface)
            $mappedResult = [];
            foreach ($products as $product) {
                $mappedResult[$product->getId()] = $product;
            }

            return $mappedResult;
        }

        public static function getKey(): string
        {
            return self::RESOURCE_LOADER_KEY;
        }
    }

**Key Points:**

* **Batch Loading**: Always load multiple resources in a single query to avoid N+1
  problems
* **ID Indexing**: The return array must be indexed by resource IDs. Sulu uses these
  IDs to match loaded resources with the ResolvableResources
* **Locale Handling**: Respect the locale parameter when loading localized entities
* **Parameter Flexibility**: Allow params to override default filters for advanced use
  cases
* **Missing Resources**: If a resource doesn't exist or isn't accessible, simply don't
  include it in the result. Sulu handles missing resources gracefully

2. Service Definition
~~~~~~~~~~~~~~~~~~~~~

Register the ResourceLoader as a service with the ``sulu_content.resource_loader`` tag:

.. code-block:: yaml

    # config/services.yaml
    services:
        App\Content\ResourceLoader\ProductResourceLoader:
            tags:
                - { name: 'sulu_content.resource_loader' }

.. note::

    With autowiring enabled (the default in Sulu), you don't need to manually add the tag.
    Sulu will automatically apply the ``sulu_content.resource_loader`` tag to all services
    implementing ``ResourceLoaderInterface``.

The service tag uses the ``getKey()`` method to automatically index the loader by its
key. This key is used by PropertyResolvers to specify which loader should fetch their
resources.

Complete Example: Product Selection Field
------------------------------------------

This example demonstrates building a complete product selection field from scratch,
showing how all components work together.

1. Entity Setup
~~~~~~~~~~~~~~~

First, define your Product entity with the necessary constants:

.. code-block:: php

    <?php

    declare(strict_types=1);

    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity]
    #[ORM\Table(name: 'app_product')]
    class Product
    {
        public const RESOURCE_KEY = 'products';

        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        private ?int $id = null;

        #[ORM\Column(type: 'string', length: 255)]
        private string $title;

        #[ORM\Column(type: 'string', length: 10)]
        private string $locale;

        #[ORM\Column(type: 'boolean')]
        private bool $published = false;

        // Getters and setters...

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getTitle(): string
        {
            return $this->title;
        }

        public function setTitle(string $title): self
        {
            $this->title = $title;
            return $this;
        }

        public function getLocale(): string
        {
            return $this->locale;
        }

        public function setLocale(string $locale): self
        {
            $this->locale = $locale;
            return $this;
        }

        public function isPublished(): bool
        {
            return $this->published;
        }

        public function setPublished(bool $published): self
        {
            $this->published = $published;
            return $this;
        }
    }

2. Repository
~~~~~~~~~~~~~

Create a repository with batch loading capabilities:

.. code-block:: php

    <?php

    declare(strict_types=1);

    namespace App\Repository;

    use App\Entity\Product;
    use Doctrine\ORM\EntityRepository;

    class ProductRepository extends EntityRepository
    {
        /**
         * @param array<string, mixed> $filters
         * @return Product[]
         */
        public function findByFilters(array $filters): array
        {
            $queryBuilder = $this->createQueryBuilder('product');

            // Filter by IDs
            if (isset($filters['ids']) && is_array($filters['ids'])) {
                $queryBuilder->andWhere('product.id IN (:ids)')
                    ->setParameter('ids', $filters['ids']);
            }

            // Filter by locale
            if (isset($filters['locale'])) {
                $queryBuilder->andWhere('product.locale = :locale')
                    ->setParameter('locale', $filters['locale']);
            }

            // Filter by published status
            if (isset($filters['published'])) {
                $queryBuilder->andWhere('product.published = :published')
                    ->setParameter('published', $filters['published']);
            }

            return $queryBuilder->getQuery()->getResult();
        }
    }

3. ResourceLoader
~~~~~~~~~~~~~~~~~

Implement the ResourceLoader using the repository:

.. code-block:: php

    <?php

    declare(strict_types=1);

    namespace App\Content\ResourceLoader;

    use App\Repository\ProductRepository;
    use Sulu\Content\Application\ResourceLoader\Loader\ResourceLoaderInterface;

    class ProductResourceLoader implements ResourceLoaderInterface
    {
        public const RESOURCE_LOADER_KEY = 'products';

        public function __construct(
            private ProductRepository $productRepository,
        ) {
        }

        public function load(array $ids, ?string $locale, array $params = []): array
        {
            $filters = [
                'ids' => $ids,
                'locale' => $locale,
                'published' => true,
            ];

            if (isset($params['filters']) && is_array($params['filters'])) {
                $filters = array_merge($filters, $params['filters']);
            }

            $products = $this->productRepository->findByFilters($filters);

            $mappedResult = [];
            foreach ($products as $product) {
                $mappedResult[$product->getId()] = $product;
            }

            return $mappedResult;
        }

        public static function getKey(): string
        {
            return self::RESOURCE_LOADER_KEY;
        }
    }

4. PropertyResolver
~~~~~~~~~~~~~~~~~~~

Implement the PropertyResolver that references the ResourceLoader:

.. code-block:: php

    <?php

    declare(strict_types=1);

    namespace App\Content\PropertyResolver;

    use App\Entity\Product;
    use App\Content\ResourceLoader\ProductResourceLoader;
    use Sulu\Content\Application\ContentResolver\Value\ContentView;
    use Sulu\Content\Application\PropertyResolver\Resolver\PropertyResolverInterface;

    class ProductSelectionPropertyResolver implements PropertyResolverInterface
    {
        public function resolve(mixed $data, string $locale, array $params = []): ContentView
        {
            if (!is_array($data) || 0 === count($data) || !array_is_list($data)) {
                return ContentView::create([], ['ids' => [], ...$params]);
            }

            $ids = $data;
            $resourceLoaderKey = $params['resourceLoader'] ?? ProductResourceLoader::getKey();

            return ContentView::createResolvablesWithReferences(
                ids: $ids,
                resourceLoaderKey: $resourceLoaderKey,
                resourceKey: Product::RESOURCE_KEY,
                view: [
                    'ids' => $ids,
                    ...$params,
                ],
                priority: 150
            );
        }

        public static function getType(): string
        {
            return 'product_selection';
        }
    }

5. Service Configuration
~~~~~~~~~~~~~~~~~~~~~~~~

Register both services:

.. code-block:: yaml

    # config/services.yaml
    services:
        # Repository
        App\Repository\ProductRepository:
            arguments:
                - '@doctrine'
                - 'App\Entity\Product'

        # ResourceLoader
        App\Content\ResourceLoader\ProductResourceLoader:
            tags:
                - { name: 'sulu_content.resource_loader' }

        # PropertyResolver
        App\Content\PropertyResolver\ProductSelectionPropertyResolver:
            tags:
                - { name: 'sulu_content.property_resolver' }

6. Template Usage
~~~~~~~~~~~~~~~~~

Add the product selection to your page template:

.. code-block:: xml

    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">

        <key>product_page</key>

        <view>templates/product_page</view>
        <controller>Sulu\Bundle\WebsiteBundle\Controller\DefaultController::indexAction</controller>

        <properties>
            <property name="title" type="text_line" mandatory="true">
                <meta>
                    <title lang="en">Title</title>
                </meta>
            </property>

            <property name="products" type="product_selection">
                <meta>
                    <title lang="en">Featured Products</title>
                </meta>
            </property>
        </properties>
    </template>

7. Twig Output
~~~~~~~~~~~~~~

Access the resolved product data in your Twig template:

.. code-block:: twig

    {# templates/product_page.html.twig #}
    <h1>{{ content.title }}</h1>

    <h2>Featured Products</h2>
    <div class="products">
        {% for product in content.products %}
            <div class="product">
                <h3>{{ product.title }}</h3>
                <p>Product ID: {{ product.id }}</p>
            </div>
        {% endfor %}
    </div>

    {# Check if any products were selected #}
    {% if content.products is empty %}
        <p>No products selected.</p>
    {% endif %}

Best Practices
--------------

**PropertyResolver:**

* Always validate input data and return an empty ContentView for invalid cases (never throw exceptions)
* Choose the appropriate ContentView factory method:

  * ``createResolvablesWithReferences()`` - Entity selections (enables cache invalidation)
  * ``createResolvable()`` - Single resource reference
  * ``create()`` - Simple data without loading

* Use standard priority values: ``-50`` (links/media), ``0`` (default), ``150`` (content entities),

**ResourceLoader:**

* **Always implement batch loading** - load all resources in a single query, never one by one
* Return results indexed by ID as required by the interface
* Handle missing resources gracefully by omitting them from results
* Implement security/permission checks here, not in PropertyResolvers
* Respect locale parameters and allow filter parameters for advanced scenarios

**Common Pitfalls:**

* ❌ Don't load resources in PropertyResolvers - that's the ResourceLoader's exclusive job
* ❌ Don't forget to index ResourceLoader results by ID

.. note::

    PropertyResolvers and ResourceLoaders are designed for read operations on the website
    frontend. For admin interfaces and write operations, use Sulu's Admin API and form
    metadata system instead.
