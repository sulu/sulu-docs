Add Data to the Resolved Content
================================

The ``ContentResolver`` collects the data for the template of a page, article or snippet from several resolvers. Sulu
ships resolvers for the template content (``template``), the SEO data (``seo``), the excerpt (``excerpt``), the entity
(``object``) and the settings (``settings``). A custom resolver adds more data to the template. The result is described
in :doc:`../bundles/content/index`.

.. note::

    A resolver works on the whole content. To add a field type for the properties of a template, see
    :doc:`property-resolver-and-resource-loader`.

Resolver
--------

A resolver implements ``ResolverInterface``:

* ``resolve(DimensionContentInterface $dimensionContent, ?array $properties = null): ?ContentView`` - Returns the data
  or ``null``, if the resolver does not apply to the content
* ``getType(): string`` - Available since Sulu 3.1. Returns the key of the data, which has to be unique
* ``getOutputPath(): ?string`` - Available since Sulu 3.1. Returns where the data is placed in the result

A resolver runs for every content entity that is resolved, also for the pages and articles which are loaded by a
selection. If ``$properties`` is set, the caller asked for specific properties. A resolver which adds none of them
should return ``null``.

The following resolver adds the reading time of an article:

.. code-block:: php

    <?php

    declare(strict_types=1);

    namespace App\Content;

    use Sulu\Content\Application\ContentResolver\Resolver\ResolverInterface;
    use Sulu\Content\Application\ContentResolver\Value\ContentView;
    use Sulu\Content\Domain\Model\DimensionContentInterface;
    use Sulu\Content\Domain\Model\TemplateInterface;

    class ReadingTimeResolver implements ResolverInterface
    {
        public function resolve(DimensionContentInterface $dimensionContent, ?array $properties = null): ?ContentView
        {
            if (null !== $properties || !$dimensionContent instanceof TemplateInterface) {
                return null;
            }

            $article = $dimensionContent->getTemplateData()['article'] ?? null;

            if (!\is_string($article)) {
                return null;
            }

            $text = \html_entity_decode(\strip_tags($article));
            $words = \preg_match_all('/\p{L}+/u', $text);

            return ContentView::create(['minutes' => \max(1, (int) \ceil($words / 200))], []);
        }

        public function getType(): string
        {
            return 'reading_time';
        }

        public function getOutputPath(): ?string
        {
            return null;
        }
    }

The ``article`` property is part of the template of the example. The template reads the data from ``extension``:

.. code-block:: twig

    {{ extension.reading_time.minutes }} min

.. note::

    In Sulu 3.0 ``ResolverInterface`` has only ``resolve()``: ``getType()`` and ``getOutputPath()`` are not called, the
    type is set in the tag, see below, and the data is always placed at ``extension.<type>``.

Output Path
-----------

Available since Sulu 3.1. ``getOutputPath()`` returns a path in brackets relative to the result. ``null`` places the
data at ``[extension][<type>]``. Other paths are for example ``[extension][reading][time]``, or an empty string, which
merges the data into the root of the result. The data of an empty path has to be an array. If a path ends with
``content``, the view, which is the second argument of ``ContentView::create()``, is written to the ``view`` key next to
it.

``getType()`` and ``getOutputPath()`` are instance methods, unlike the static ``getType()`` of the PropertyResolver. A
decorator can delegate both to the resolver it decorates, like the ``RoutableTemplateResolver`` does.

Register the Resolver
---------------------

Available since Sulu 3.1. If you're using the default `services.yaml configuration from Symfony`_ with
autoconfiguration, the resolver is tagged with ``sulu_content.content_resolver`` automatically. Otherwise tag the
service yourself:

.. code-block:: yaml

    # config/services.yaml
    services:
        App\Content\ReadingTimeResolver:
            tags:
                - { name: 'sulu_content.content_resolver' }

In Sulu 3.0 the resolver is not autoconfigured and the tag needs the type. Sulu 3.1 ignores the ``type`` attribute, so
this tag works in both versions:

.. code-block:: yaml

    # config/services.yaml
    services:
        App\Content\ReadingTimeResolver:
            tags:
                - { name: 'sulu_content.content_resolver', type: 'reading_time' }

Set the ``priority`` of the tag to order the resolvers. With autoconfiguration, ``#[AsTaggedItem(priority: 10)]`` on
the class works as well. If two resolvers have the same type, the data of the one with the lower priority (at equal
priority, the one registered later) replaces the other, so do not reuse a type of Sulu like ``seo``.

Available since Sulu 3.1. Resolvers can write to the same output path. On a key collision the resolver with the higher
priority wins. The keys ``resource``, ``content``, ``view`` and ``extension`` of the result can not be written at the
root, whatever the priority.

.. _services.yaml configuration from Symfony: https://symfony.com/doc/current/service_container.html#service-container-services-load-example
