Provider for CKEditor Internal-Sulu-Link
========================================

`LinkProvider` are used to load data for the "Internal-Sulu-Link" Plugin for
the CKEditor. It returns an array of `LinkItem` instances, identified by ids
which will be passed to the `TeaserProviderInterface::preload` function.
This feature can be used by the CKEditor Plugin "Sulu-Internal-Link" or
by adding Markup to your twig-templates (see following example and the
chapter :doc:`../bundles/markup/index`).

.. code-block:: html

    <sulu-link provider="page" href="123-123-123"/>


The LinkItem consists of the following properties:

* id
* title
* url
* published

Example
-------

This example assumes that the entities will be selected by a datagrid.
For this we already build an abstract implementation which can be
configured with the `LinkConfiguration`.

.. code-block:: php

    namespace AppBundle\Link;

    use Sulu\Bundle\PageBundle\Markup\Link\LinkConfiguration;
    use Sulu\Bundle\PageBundle\Markup\Link\LinkItem;
    use Sulu\Bundle\PageBundle\Markup\Link\LinkProviderInterface;

    class LinkProvider implements LinkProviderInterface
    {
        /**
         * {@inheritdoc}
         */
        public function getConfiguration()
        {
            return new LinkConfiguration(
                'app.link',
                'ckeditor/link/list@sulupage',
                [
                    'url' => '...', // your api url to load list
                    'hrefUrl' => '...<%=link.href%>', // your api url to load single item
                    'idKey' => 'id',
                    'titleKey' => 'name',
                    'publishedExpression' => [new DisplayCondition('published', DisplayCondition::OPERATOR_EQUAL, true)],
                    'resultKey' => '...', // your api result-key
                    'searchFields' => ['title'],
                    'matchings' => [
                        [
                            'content' => 'public.title',
                            'name' => 'title',
                        ],
                    ],
                ]
            );
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

        public function find(array $ids, $locale)
        {
            if (0 === count($ids)) {
                return [];
            }

            $items = ...; // load items by id
            foreach ($items as $item) {
                $result[] = new Teaser(...); // create teaser foreach item
            }

            return $result;
        }
    }

Now you can create a service for this class and add the tag
`<tag name="sulu.link.provider" alias="{your link-type}"/>`.

How to create a custom link-select component?
---------------------------------------------

A link-select aura-component, which provides the ability to select or deselect
items.

The following example is a simple (and not complete) example. If you need a full
example please take a look at the components `ckeditor/link/list@sulupage`
or `ckeditor/link/page@sulupage` or `ckeditor/link/article@suluarticle`.

.. code-block:: javascript

    define(function() {

        'use strict';

        return {

            defaults: {
                options: {
                    link: {},
                    locale: null,
                    webspace: null,
                    setHref: function(id, title, published) {
                    },
                    selectCallback: function(id, title, published) {
                    }
                }
            },

            initialize: function() {
                this.resolveHref();
                this.render();
            },

            resolveHref: function() {
                if (!this.options.link.href) {
                    this.options.setHref();

                    return;
                }

                this.loadSingle(this.options.link.href, this.options.locale).then(function(data) {
                    this.options.setHref(data.id, data.title, data.published);
                }.bind(this));
            },

            loadSingle: function(href, locale) {
                return ...; // load selected item by href
            },

            render: function() {
                var $container = $(this.templates.contentDatasource());
                this.$el.append($container);

                this.sandbox.start(
                    [
                        {
                            name: 'datagrid@husky',
                            options: {
                                el: '#href-select',
                                instanceName: 'link',
                                url: ..., // your api url
                                resultKey: ..., // your api result-key
                                clickCallback: function(id, item) {
                                    this.options.selectCallback(id, item.title, true);
                                }.bind(this),
                                selectedCounter: false,
                                paginationOptions: {
                                    dropdown: {
                                        limit: 20
                                    }
                                },
                                viewOptions: {
                                    table: {
                                        selectItem: false
                                    }
                                },
                                matchings: [
                                    {
                                        content: 'public.id',
                                        name: 'id'
                                    },
                                    {
                                        content: 'public.title',
                                        name: 'title'
                                    }
                                ]
                            }
                        }
                    ]
                );
            }
        };
    });
