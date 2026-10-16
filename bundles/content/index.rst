ContentBundle
=============

The SuluContentBundle provides the content storage and the publishing workflow that pages, articles and snippets
build on. A content entity is split into the entity itself and its ``DimensionContent`` records, which hold the
locale-, stage- and version-aware content. :doc:`../../cookbook/extend-dimension-content` explains how to extend them.

Content Resolving
-----------------

The ``ContentResolver`` (``Sulu\Content\Application\ContentResolver\ContentResolverInterface``, service
``sulu_content.content_resolver``) turns a ``DimensionContent`` into the data that is passed to the Twig template. The
result is an array with these keys:

.. list-table::
    :header-rows: 1

    * - Key
      - Description
    * - ``resource``
      - The content entity, for example the page.
    * - ``content``
      - The values of the properties of the template.
    * - ``view``
      - Additional data of the properties, which is needed to render them.
    * - ``extension``
      - The data of the other resolvers, for example ``extension.seo``, ``extension.excerpt`` and
        ``extension.object``. Since Sulu 3.1 a resolver can choose another place.

The settings resolver writes the settings the entity has to the root of the result: ``availableLocales``,
``mainWebspace`` (articles), ``template``, ``author``, ``authored``, ``lastModified`` and ``shadowBaseLocale``. A
``ContentController`` passes all of these to the template as variables and adds ``localizations``.

The optional ``$properties`` argument of ``resolve()`` is a map of ``output key => source path`` of type
``array<string, string>``. The source path is the name of a property of the template or a path with the prefix ``seo.``,
``excerpt.`` or ``object.``. The requested values are placed at the root of the result under the key of the map:

.. code-block:: php

    $resolved = $contentResolver->resolve($dimensionContent, [
        'title' => 'title',
        'description' => 'excerpt.description',
    ]);

    // $resolved['title'], $resolved['description']

This is how the ``properties`` argument of the Twig functions ``sulu_page_load``, ``sulu_article_load`` and
``sulu_snippet_load_by_area`` works, see :doc:`../../reference/twig-extensions/functions/sulu_page_load`.

Content which is loaded by a selection, like the pages of a ``page_selection``, is resolved in turn. This nesting is
limited by the ``max_depth`` option. Each level of nesting is one step: a page in a selection is step 1, an image of
that page is step 2. Beyond the limit the resources are replaced by ``null``, so a selection of two pages becomes a
list of two ``null`` values:

.. code-block:: yaml

    # config/packages/sulu_content.yaml
    sulu_content:
        content_resolver:
            max_depth: 5 # default

To add data to the result, see :doc:`../../cookbook/custom-content-resolver`.

.. toctree::
    :maxdepth: 2

    request-workflow
