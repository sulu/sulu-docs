ReferenceBundle
===============

The ReferenceBundle is tasked with tracking references among entities within the application.
It enables developers and maintainers to quickly determine the relationships between entities and understand the manner
and location in which an entity is utilized. Presently, the ReferenceBundle is capable of monitoring the usage of Snippets and
Media within PHPCR entities such as `pages` and `snippets`. These references are managed distinctly for the draft
state within the `admin context` and the live state within the `website context`.

The main reason we need this bundle is that, unlike traditional database references, our content management system
operates on an unstructured data model. Therefore, we cannot rely solely on database references, which are usually preferred.
It is essential to note that the ReferenceBundle should only be used for unstructured data, where database relations are
not feasible.

Content maintainers are able to see the references to a specific entity in the `Insights` tab of an entity like `Snippet`.

.. figure:: ../img/snippet-insights.png
    :alt: Snippet References

    Snippet References

Refresh references
------------------

The references are automatically updated upon saving an entity. You also have the option to manually update the
references by executing the `bin/console sulu:reference:refresh` command. This command optionally accepts the
<resource-key> argument. When this argument is provided, only the references for the specified resource key will be refreshed.

.. code-block:: bash

    bin/console sulu:reference:refresh <resource-key>

.. note::

    Please note that references are only refreshed for the current context. To refresh the references for both the
    admin and website contexts, you will need to execute the command twice via the `bin/adminconsole` and the `bin/websiteconsole`.

Integrating references for custom content-types
-----------------------------------------------

To integrate the ReferenceBundle for custom content-types, you need to implement the `ReferenceContentTypeInterface` in your
content-type class. The interface requires you to implement the `getReferences` method. The method already receives the
`ReferenceCollector` which you can use to add references to the collector.

Example implementation for a custom content-type:

.. code-block:: php

        public function getReferences(PropertyInterface $property, ReferenceCollectorInterface $referenceCollector, string $propertyPrefix = ''): void
        {
            $data = $property->getValue();
            if (!\is_array($data) || !isset($data['id'])) {
                return;
            }

            $referenceCollector->addReference(
                CustomEntity::RESOURCE_KEY,
                (string) $data['id'],
                $propertyPrefix . $property->getName()
            );
    }

Extending the Admin View with a References Table
------------------------------------------------

To extend the admin view by adding a references table, follow these steps:

	1.	Inject the `ReferenceViewBuilderFactoryInterface`: Inject this interface into your custom Admin class. This will allow you to utilize the necessary methods to create the references view.
	2.	Create the Reference List View: Use the `createReferenceListViewBuilder` method to create the list view for the references table.

Here’s an example implementation from the `SnippetAdmin` class, demonstrating how to add the references tab to your custom admin view:

.. code-block:: php

    if ($this->referenceViewBuilderFactory->hasReferenceListPermission()) {
        $viewCollection->add(
            $this->referenceViewBuilderFactory
                ->createReferenceListViewBuilder(
                    $insightsResourceTabViewName . '.reference',
                    '/references',
                    SnippetDocument::RESOURCE_KEY
                )
                ->setParent($insightsResourceTabViewName)
        );
    }

The `hasReferenceListPermission` method ensures that the current user has permission to view the references list.
The `createReferenceListViewBuilder` method is used to create the view. It takes three parameters:

    - The name of the new view, usually appended with .reference to indicate it is a reference view.
    - The URL path for the references table.
    - The resource key identifies the type of resource being referenced.

The setParent method sets the parent view to integrate the references table into the existing admin view.