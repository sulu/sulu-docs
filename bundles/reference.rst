ReferenceBundle
===============

The ReferenceBundle tracks where an entity is used. For the content of pages, snippets and articles, Sulu stores
one reference per used entity and property, separately for the ``draft`` and the ``live`` stage.

The main reason we need this bundle is that content is stored as JSON data within dimension entities.
Therefore, we cannot rely solely on database foreign key references.
It is essential to note that the ReferenceBundle should only be used for unstructured JSON content data,
where database relations are not feasible.

Content maintainers see where a media is used in the ``References`` tab below the ``Insights`` tab of the media.

Refresh references
------------------

References are updated when a page, snippet or article is saved. The ``sulu:reference:refresh`` command updates
all references. The optional ``resource-key`` argument limits the refresh to one resource key, for example
``pages``, ``snippets`` or ``articles``.

.. code-block:: bash

    bin/console sulu:reference:refresh <resource-key>

Run it without arguments after an upgrade that makes more content register references, for example the
``teaser_selection`` property type.

Integrating references for custom property types
------------------------------------------------

A property resolver (see :doc:`../cookbook/property-resolver-and-resource-loader`) passes the references of its
value to the ``ContentView``. Sulu adds the path of each reference, for example ``blocks.0.product``.

This resolver stores a reference to the ``products`` resource for the id saved in the property:

.. code-block:: php

    <?php

    declare(strict_types=1);

    namespace App\Content\PropertyResolver;

    use Sulu\Content\Application\ContentResolver\Value\ContentView;
    use Sulu\Content\Application\ContentResolver\Value\Reference;
    use Sulu\Content\Application\PropertyResolver\Resolver\PropertyResolverInterface;

    class ProductPropertyResolver implements PropertyResolverInterface
    {
        public function resolve(mixed $data, string $locale, array $params = []): ContentView
        {
            if (!\is_string($data) && !\is_int($data)) {
                return ContentView::create(null, $params);
            }

            return ContentView::createWithReferences(
                $data,
                $params,
                [new Reference($data, 'products')],
            );
        }

        public static function getType(): string
        {
            return 'product';
        }
    }

For a value loaded by a resource loader, use ``ContentView::createResolvableWithReferences`` or
``ContentView::createResolvablesWithReferences``, see :doc:`../cookbook/property-resolver-and-resource-loader`.

Integrating references for a custom content entity
--------------------------------------------------

Pages, snippets and articles are refreshed by a service implementing
``Sulu\Bundle\ReferenceBundle\Application\Refresh\ReferenceRefresherInterface``. Implement it to track the
references of your own content entity:

- ``public static function getResourceKey(): string`` returns the resource key of the dimension content, for
  example ``products``.
- ``refresh(?array $filter = null): \Generator`` resolves the content views of the matching dimension contents.
  It adds each reference to a ``ReferenceCollector`` and calls ``persistReferences()``.

``refresh()`` must yield each dimension content after its references are persisted. Sulu flushes after every 100
yielded objects. The filter holds the ``resourceId``, ``resourceKey``, ``locale`` and ``stage`` of the saved
content. ``null`` means all contents.

With autoconfiguration, services implementing the interface get the ``sulu_reference.refresher`` tag.

When a dimension content is saved, Sulu sends a ``RefreshReferenceMessage`` to the ``sulu_message_bus``. The
refresher with the same resource key handles it in the same request.

Do not route the message to an asynchronous transport. The listener expects the message to be handled right away and
throws otherwise.

The ``SnippetReferenceRefresher`` is a complete example.

Extending the Admin View with a References Table
------------------------------------------------

To show the references of your entity, add a references table to its admin views:

1. Inject the ``ReferenceViewBuilderFactoryInterface`` into your custom Admin class.
2. Use its ``createReferenceListViewBuilder`` method to create the list view for the references table.

``MediaAdmin`` adds the references tab below its ``Insights`` tab:

.. code-block:: php

    if ($this->referenceViewBuilderFactory->hasReferenceListPermission()) {
        $viewCollection->add(
            $this->referenceViewBuilderFactory
                ->createReferenceListViewBuilder(
                    $insightsResourceTabViewName . '.reference',
                    '/references',
                    MediaInterface::RESOURCE_KEY
                )
                ->setParent($insightsResourceTabViewName)
        );
    }

``hasReferenceListPermission`` checks that the current user may view the references list.
``createReferenceListViewBuilder`` takes the following parameters:

- The name of the new view, usually appended with .reference to indicate it is a reference view.
- The URL path for the references table.
- The resource key identifies the type of resource being referenced.
- Optional, the router attribute that holds the resource id, ``id`` by default.

The setParent method sets the parent view to integrate the references table into the existing admin view.
