Provider for Internal-Links
========================================

`LinkProvider` are used to load data for internal links. It returns an
array of `LinkItem` instances, identified by ids which will be passed
to the `LinkProviderInterface::preload` function.
This feature is used in the CKEditor Plugin, in the Markup for twig-templates
(see :doc:`../bundles/markup/index`) and in the `Link` content-type
(see :doc:`../reference/content-types/link`).

The LinkItem consists of the following properties:

* id
* title
* url
* published

Example
-------

This example assumes that the entities will be selected by a datagrid.
For this we already build an abstract implementation which can be
configured with the `LinkConfigurationBuilder`.

.. code-block:: php

    <?php

    namespace AppBundle\Link;

    use Sulu\Bundle\PageBundle\Markup\Link\LinkConfiguration;
    use Sulu\Bundle\MarkupBundle\Markup\Link\LinkConfigurationBuilder;
    use Sulu\Bundle\PageBundle\Markup\Link\LinkItem;
    use Sulu\Bundle\PageBundle\Markup\Link\LinkProviderInterface;

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

Now you can create a service for this class and add the tag with the corresponding
resourceKey `<tag name="sulu.link.provider" alias="{resourceKey}"/>`.

Furthermore you can register the `LinkProvider` in the `linkTypeRegistry` to select internal-links
in the CKEditor Plugin and the `Link` content-type.