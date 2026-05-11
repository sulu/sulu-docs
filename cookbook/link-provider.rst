Provider for custom link type
=============================

``LinkProvider`` services are used to resolve data for different types of internal links.
The services are used in different parts of the system, including the `Link` property type
(see :doc:`../reference/property-types/link`), the internal link plugin of the CKEditor and
the ``<sulu-link>`` tag inside of twig-templates (see :doc:`../bundles/markup/link`).

The ``LinkProvider::preload`` method is responsible for resolving an array of ``LinkItem``
instances for the given arguments. Each ``LinkItem`` consists of the following properties:

* id
* title
* url
* published

Example
-------

To register a ``LinkProvider`` service for a custom link type, you create a service that
implements the ``LinkProviderInterface`` and tag it with the respective resource key:
``<tag name="sulu.link.provider" alias="{resourceKey}"/>``

The ``LinkProvider::getConfigurationBuilder`` method must return a ``LinkConfigurationBuilder``
that defines how entities of this link type are selected in the administration interface.

.. code-block:: php

    <?php

    namespace App\Link;

    use Sulu\Bundle\MarkupBundle\Markup\Link\LinkConfigurationBuilder;
    use Sulu\Bundle\MarkupBundle\Markup\Link\LinkItem;
    use Sulu\Bundle\MarkupBundle\Markup\Link\LinkProviderInterface;
    use Symfony\Contracts\Translation\TranslatorInterface;

    class LinkProvider implements LinkProviderInterface
    {
        public function __construct(
            private TranslatorInterface $translator,
        ) {
        }

        public function getConfigurationBuilder(): LinkConfigurationBuilder
        {
            return LinkConfigurationBuilder::create()
                ->setTitle($this->translator->trans('app.custom_link_type', [], 'admin'))
                ->setResourceKey('custom_resource_key') // the resourceKey of the entity that should be loaded
                ->setListAdapter('column_list')
                ->setDisplayProperties(['title'])
                ->setOverlayTitle($this->translator->trans('app.custom_link_overlay_title', [], 'admin'))
                ->setEmptyText($this->translator->trans('app.no_custom_link_selected', [], 'admin'))
                ->setIcon('su-document');
        }

        public function preload(array $hrefs, string $locale, bool $published = true): iterable
        {
            if (0 === \count($hrefs)) {
                return [];
            }

            $items = ...; // load items by id
            $result = [];
            foreach ($items as $item) {
                $result[] = new LinkItem($item->getId(), $item->getTitle(), $item->getUrl(), $item->isPublished());
            }

            return $result;
        }
    }

Register the service and tag it with your resource key in ``config/services.yaml``:

.. code-block:: yaml

    App\Link\CustomLinkProvider:
        tags:
            - { name: sulu.link.provider, alias: custom_resource_key }

Custom target options
---------------------

By default, link targets ``_blank``, ``_self``, ``_parent`` and ``_top`` are available.
Use ``setTargets()`` on the builder to restrict or replace the available options:

.. code-block:: php

    ->setTargets(['_blank', '_self'])

The admin UI translates each target value using the key pattern
``sulu_admin.link_target{value}`` — for example ``_blank`` becomes
``sulu_admin.link_target_blank``.

To **add a new target** or **override an existing label**, add the key to your project's
admin translation file (e.g. ``translations/admin.en.json``):

.. code-block:: json

    {
        "sulu_admin.link_target_blank": "Open in new tab",
        "sulu_admin.link_target_self": "Open in same tab",
        "sulu_admin.link_target_popup": "Open in popup"
    }

Then register the new value in ``setTargets()``:

.. code-block:: php

    ->setTargets(['_blank', '_self', '_popup'])

Custom overlay via JavaScript
-----------------------------

If the entities of the new link type cannot be selected via a standard list, you can replace
the default list overlay with a custom React component. Register your component in the
``linkOverlayRegistry`` **before** the admin bundle initialises its link types. The registry
picks it up automatically when it processes the PHP-provided ``internalLinkTypes`` configuration:

.. code-block:: javascript

    import linkOverlayRegistry from 'sulu-admin-bundle/containers/Link/registries/linkOverlayRegistry';

    linkOverlayRegistry.add('custom_resource_key', CustomLinkTypeOverlay);
