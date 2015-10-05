DataProvider for SmartContent
=============================

DataProviders are used to load data for SmartContent. It returns data filtered
by a configuration array. This array can be configured with an overlay in the
backend form.

This configuration array includes following values:

.. list-table::
    :header-rows: 1

    * - Name
      - Description
    * - dataSource
      - Additional constraint - like page-"folder".
    * - tags
      - Multiple selection of tags, which a item should have.
    * - tagOperator
      - The item has any or all of the selected tags.
    * - categories
      - Multiple selection of categories, which a item should have.
    * - categoryOperator
      - The item has any or all of the selected categories.

Tags (websiteTags) and Categories (websiteCategories) can also be "injected" by
GET parameters from the website. This can be handled separately from the
admin-selected. Also different operators (websiteTagsOperator and
websiteCategoryOperator) are available.

Additional features, which can be provider with a DataProvider:

.. list-table::
    :header-rows: 1

    * - Name
      - Description
    * - presentAs
      - Value can be used in the website for display options - like one or two
        column - these values can be freely configured by developers.
    * - page & pageSize
      - Pagination of items.
    * - limit
      - Maximum items for (if pagination is active) over all pages or overall.

How to create a custom DataProvider?
------------------------------------

To create a custom data provider you simply have to create a service which
implements the Interface `DataProviderInterface`. This Interface provides
functions to resolve the configured filters for the backend API with
standardized objects and for the website with array and entity access.
Additionally the DataProvider returns a configuration object to enable or
disable features.

For ORM DataProvider there exists some abstraction layer. This layer provides the
implementation of basic DataProvider functionality and Database query.

If you want to create a DataProvider for the ExampleEntity you have todo
following steps.

1. Repository
~~~~~~~~~~~~~

Implement Interface `DataProviderRepositoryInterface` to provide needed function
findByFilters. If the default implementation is enough for you you can include
the trait `DataProviderRepositoryTrait` which needs the functions
`createQueryBuilder` (is default in repositories) and `appendJoins` there you
are able to configure eager loading for the entity.

The rest of the functionality and Query generation is done in the Trait.

.. code-block:: php

  <?php

    use Sulu\Component\SmartContent\Orm\DataProviderRepositoryInterface;
    use Sulu\Component\SmartContent\Orm\DataProviderRepositoryTrait;

  /**
   * Repository for the ExampleEntities.
   */
  class ExampleRepository extends EntityRepository implements DataProviderRepositoryInterface
  {
      use DataProviderRepositoryTrait;

      /**
       * {@inheritdoc}
       */
      public function appendJoins(QueryBuilder $queryBuilder)
      {
          $queryBuilder->addSelect('type')->leftJoin('entity.type', 'type');
      }
  }

.. note::

    Be sure that the returned entities has valid serialization configuration for
    JMS\Serializer.


2. DataItem
~~~~~~~~~~~

The DataItem will be used in the backend to display the filtered items. This
class implements the Interface `ItemInterface`.

.. code-block:: php

    <?php

    use Sulu\Component\SmartContent\ItemInterface;

    /**
     * Represents example item in example data provider.
     *
     * @ExclusionPolicy("all")
     */
    class ExampleDataItem implements ItemInterface
    {
        /**
         * @var Example
         */
        private $entity;

        public function __construct(Example $entity)
        {
            $this->entity = $entity;
        }

        /**
         * {@inheritdoc}
         *
         * @VirtualProperty
         */
        public function getId()
        {
            return $this->entity->getId();
        }

        /**
         * {@inheritdoc}
         *
         * @VirtualProperty
         */
        public function getTitle()
        {
            return $this->entity->getTitle();
        }

        /**
         * {@inheritdoc}
         *
         * @VirtualProperty
         */
        public function getImage()
        {
            return;
        }

        /**
         * {@inheritdoc}
         */
        public function getResource()
        {
            return $this->entity;
        }
    }

3. DataProvider
~~~~~~~~~~~~~~~

Also the DataProvider is mostly abstracted by the SmartContent Component. The
optimize the configuration you can disable or enable the form-elements to avoid
filtering for that values.

.. code-block:: php

    <?php

    use JMS\Serializer\SerializerInterface;
    use Sulu\Component\SmartContent\Orm\BaseDataProvider;
    use Sulu\Component\SmartContent\Orm\DataProviderRepositoryInterface;

    /**
     * Example DataProvider for SmartContent.
     */
    class ExampleDataProvider extends BaseDataProvider
    {
        public function __construct(DataProviderRepositoryInterface $repository, SerializerInterface $serializer)
        {
            parent::__construct($repository, $serializer);

            $this->configuration = $this->initConfiguration(true, true, true, true, true, []);
        }

        /**
         * {@inheritdoc}
         */
        protected function decorateDataItems(array $data)
        {
            return array_map(
                function ($item) {
                    return new ExampleDataItem($item);
                },
                $data
            );
        }
    }

4. Service Definition
~~~~~~~~~~~~~~~~~~~~~

Define a service with your Repository and DataProvider and add the tag
`sulu.smart_content.data_provider` with a alias to your DataProvider service
definition.

.. code-block:: xml

        <service id="sulu_example.example_repository" class="Sulu\Bundle\ExampleBundle\Entity\ExampleRepository"
                 factory-method="getRepository" factory-service="doctrine">
            <argument>%sulu_example.example.entity%</argument>
        </service>

        <service id="sulu_example.smart_content.data_provider.example" class="Sulu\Bundle\ExampleBundle\SmartContent\ExampleDataProvider">
            <argument type="service" id="sulu_example.example_repository"/>
            <argument type="service" id="serializer"/>

            <tag name="sulu.smart_content.data_provider" alias="example"/>
        </service>

Afterwards you can use your new DataProvider within a normal SmartContent
property.
