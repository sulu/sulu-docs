Provider for Teaser-Selection
=============================

TeaserProvider are used to load data for Teaser-Selection. It returns an array
of Teaser instances by an array of ids. This array can be configured by a
content-type in the Sulu-Admin.

The Teaser-Object consists of following properties:

* id
* type (e.g. content or article)
* locale
* title
* description
* moreText (link-text)
* mediaId
* url

Example
-------

This example assumes that the entities will be select by a datagrid.
For this we already build a abstract implementation which can be
configured wil the `TeaserConfiguration`.

.. code-block:: php

    namespace AppBundle\Teaser;

    use Sulu\Bundle\ContentBundle\Teaser\Configuration\TeaserConfiguration;
    use Sulu\Bundle\ContentBundle\Teaser\Provider\TeaserProviderInterface;
    use Sulu\Bundle\ContentBundle\Teaser\Teaser;

    class TeaserProvider implements TeaserProviderInterface
    {
        public function getConfiguration()
        {
            return new TeaserConfiguration(
                'app.teaser',
                'teaser-selection/list@sulucontent',
                [
                    'url' => '...', // your api url
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
`<tag name="sulu.teaser.provider" alias="<your teaser-type>"/>`.

How to create a custom teaser-select component?
-----------------------------------------------

A teaser-select component is a simple aura-component which provides the ability
to select or deselect items.

The following example is a simple (and not complete) example. If you need a full
example please take a look at the components `teaser-selection/content@sulucontent`
or `teaser-selection/list@sulucontent`.

.. code-block:: javascript

    define(function() {

        'use strict';

        return {
            initialize: function() {
                var $container = $('<div/>');
                this.$el.append($container);

                this.sandbox.start(
                    [
                        {
                            name: 'column-navigation@husky',
                            options: {
                                el: $container,
                                url: ..., // your api url
                                instanceName: this.options.instanceName,
                                actionIcon: 'fa-plus-circle',
                                resultKey: ..., // your api result-key
                                showOptions: false,
                                responsive: false,
                                markable: true,
                                sortable: false,
                                premarkedIds: _.map(this.options.data, function(item) {
                                    return item.id;
                                }),
                                actionCallback: function(item) {
                                    this.options.selectCallback({type: '...', id: item.id}); // your teaser-type
                                }.bind(this)
                            }
                        }
                    ]
                );
            }
        };
    });
