Provider for custom link type
=============================

``LinkProvider`` services are used to resolve data for different types of internal links.
The services are used in different parts of the system, including the `Link` content-type
(see :doc:`../reference/content-types/link`), the internal link plugin of the CKEditor and
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

If the entities of the new link type should be selected via a list in the administration interface,
the ``LinkProvider::getConfiguration`` method must return the configuration that is used for
the list.

.. code-block:: php

    <?php

    namespace AppBundle\Link;

    use Sulu\Bundle\MarkupBundle\Markup\Link\LinkConfigurationBuilder;
    use Sulu\Bundle\MarkupBundle\Markup\Link\LinkItem;
    use Sulu\Bundle\MarkupBundle\Markup\Link\LinkProviderInterface;

    class LinkProvider implements LinkProviderInterface
    {
        /**
         * {@inheritdoc}
         */
        public function getConfiguration()
        {
            return LinkConfigurationBuilder::create()
                ->setTitle($this->translator->trans('sulu_page.pages', [], 'admin'))
                ->setResourceKey('...') // the resourceKey of the entity that should be loaded
                ->setListAdapter('column_list')
                ->setDisplayProperties(['title'])
                ->setOverlayTitle($this->translator->trans('sulu_page.single_selection_overlay_title', [], 'admin'))
                ->setEmptyText($this->translator->trans('sulu_page.no_page_selected', [], 'admin'))
                ->setIcon('su-document')
                ->getLinkConfiguration();
        }

        /**
         * {@inheritdoc}
         */
        public function preload(array $hrefs, $locale, $published = true)
        {
            if (0 === count($hrefs)) {
                return [];
            }

            $items = ...; // load items by id
            foreach ($items as $item) {
                $result[] = new LinkItem(...); // create link-item foreach item
            }

            return $result;
        }
    }

If the entities of the new link type cannot be selected via a list, the ``LinkProvider::getConfiguration``
method of your service must return ``null`` and you need to register a custom overlay via
the ``linkTypeRegistry`` Javascript service:

.. code-block:: javascript

    import linkTypeRegistry from 'sulu-admin-bundle/containers/Link/registries/linkTypeRegistry';

    linkTypeRegistry.add('custom_resource_key', CustomLinkTypeOverlay, translate('app.custom_translation_key'));
