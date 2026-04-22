ArticleBundle
=============

The ArticleBundle provides a dedicated space for managing content-rich entities such as blog posts,
news articles, or guides. Unlike pages, which are organized in a hierarchical tree, articles are
managed as a flat list — making it well-suited for large volumes of content that don't belong to
a fixed site structure.

Articles support drafting, publishing, versioning, localizations (including shadow and ghost pages),
SEO and excerpt management, author and publication date handling, as well as search, filter, and
sort capabilities in the Sulu admin.

Groups
------

Groups allow you to organize article templates into separate sections in the Sulu admin interface.
Each group gets its own navigation entry and list view. A group is assigned to a template by adding
the ``<group>`` tag to the template XML:

.. code-block:: xml

    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">

        <key>blog</key>
        <group>blog</group>

        <!-- ... -->

    </template>

All templates sharing the same group identifier will appear together under one admin section.
Templates without a ``<group>`` tag are placed into the ``default`` group.

The group label shown in the admin is resolved via the translation key
``sulu_admin.template_group.<group_identifier>``. If no translation is found, the group
identifier is used with its first letter capitalized.

To provide a custom label, add the translation key to your admin translation files:

.. code-block:: js

    // translations/admin.en.json
    {
        "sulu_admin.template_group.blog": "Blog",
        "sulu_admin.template_group.guide": "Guides"
    }

Webspace Configuration
----------------------

Articles are global objects — similar to snippets — that can be assigned to one or more
webspaces. Each article has a main webspace and optionally one or more additional webspaces;
it is accessible via all of them. The active webspaces per article can be configured
individually in the article's **Settings** tab in the Sulu admin, or set globally via
``config/packages/sulu_article.yaml``. Note that ``default_main_webspace`` is required when
using the article bundle.

To avoid duplicate content issues, Sulu will automatically set a canonical tag pointing to
the main webspace URL when an article is accessed via an additional webspace URL.

Both options can be provided as a simple string/list (applies to all locales) or as a
locale-keyed mapping. The ``default`` key is used as a fallback for locales with no explicit
entry:

.. code-block:: yaml

    # config/packages/sulu_article.yaml

    # Same webspaces for all locales
    sulu_article:
        default_main_webspace: my_webspace
        default_additional_webspaces:
            - my_second_webspace
            - my_third_webspace

.. code-block:: yaml

    # config/packages/sulu_article.yaml

    # Different webspaces per locale
    sulu_article:
        default_main_webspace:
            default: my_webspace
            de: my_webspace_de
        default_additional_webspaces:
            default:
                - my_second_webspace
            de:
                - my_second_webspace_de

.. note::

    The webspace settings per article can be overridden individually in the article's settings
    tab in the Sulu admin by enabling "Customize webspace settings".

.. warning::

    If you change ``default_main_webspace`` or ``default_additional_webspaces`` after articles
    have already been created, the existing articles are **not updated automatically**. You need
    to create a database migration that applies the new defaults to all articles where the editor
    has not enabled "Customize webspace settings" (``customizeWebspaceSettings = false``).

    **Updating the main webspace:** Update the ``mainWebspace`` column in
    ``ar_article_dimension_contents`` for all rows where ``customizeWebspaceSettings`` is
    ``false``.

    **Adding an additional webspace:** Insert a new row into
    ``ar_article_dimension_content_additional_webspaces`` for every
    ``ar_article_dimension_contents`` record where ``customizeWebspaceSettings`` is ``false``.
    The ``name`` column holds the webspace key and ``article_dimension_content_id`` references
    the dimension content record.

    **Removing an additional webspace:** Delete all rows from
    ``ar_article_dimension_content_additional_webspaces`` that match the removed webspace key in
    the ``name`` column and belong to a dimension content record where
    ``customizeWebspaceSettings`` is ``false``.
