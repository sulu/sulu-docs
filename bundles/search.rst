SearchBundle
============

The SuluSearchBundle provides the search of the administration interface and of the website. It is
built on `SEAL`_ (the search abstraction layer of the CMS-IG) and works with Loupe, Elasticsearch,
Opensearch, Meilisearch, Algolia, Solr, Redis, Typesense and more.

The bundle contains two indexes:

- ``admin``: the draft content of pages, articles, snippets, contacts, accounts, categories, media and
  collections. It is searched in the administration interface. A user only finds documents whose
  ``securityContext`` field names a security context on which the user has the ``edit`` permission. The
  permissions of single pages are not checked.
- ``website``: the live content of pages and articles. It is searched by the website search.

Available since Sulu 3.1. A snippet whose template is in a group other than ``default`` (see
:ref:`templates-template-groups`) is found with the ``edit`` permission on the security context of that group.
In Sulu 3.0 every snippet uses the security context of the snippets.

Search Engine
-------------

The engine is configured with the ``cmsig_seal`` configuration of SEAL. The skeleton reads the adapter
from the ``SEAL_DSN`` environment variable:

.. code-block:: yaml

    # config/packages/cmsig_seal.yaml
    cmsig_seal:
        schemas:
            default:
                dir: '%kernel.project_dir%/config/schemas'
                engine: default
        engines:
            default:
                adapter: '%env(resolve:SEAL_DSN)%'

.. code-block:: bash

    # .env
    SEAL_DSN=loupe://%kernel.project_dir%/var/indexes

The SearchBundle registers the schemas of the ``admin`` and ``website`` index itself. The ``schemas``
entry above is only needed for your own indexes.

The skeleton installs the `Loupe`_ adapter (``cmsig/seal-loupe-adapter``), which stores the index in the
filesystem and needs the ``pdo_sqlite`` PHP extension. To use another search engine, install its
adapter package and change the DSN:

.. code-block:: bash

    composer require cmsig/seal-elasticsearch-adapter

.. code-block:: bash

    # .env
    SEAL_DSN="elasticsearch://127.0.0.1:9200"

The ``.env`` of the skeleton lists the DSN of the other adapters: ``meilisearch://``, ``algolia://``,
``opensearch://``, ``redis://``, ``solr://`` and ``typesense://``. The adapter packages are named
``cmsig/seal-<name>-adapter``. See the `SEAL documentation`_ for the DSN and the options of each
adapter.

.. note::

    With a filesystem based adapter like Loupe every server has its own index. Use a search engine that
    runs as a service when you run Sulu on multiple servers, see :doc:`../cookbook/multi-server-setup`.

Creating the Indexes
--------------------

The indexes are created and dropped with the commands of the SEAL bundle, see the `SEAL documentation`_
for their options:

.. code-block:: bash

    php bin/console cmsig:seal:index-create
    php bin/console cmsig:seal:index-drop --force
    php bin/console cmsig:seal:reindex

``index-create`` only creates the missing indexes. ``index-drop`` does nothing without ``--force``, and
leaves the indexes empty, so run the reindex afterwards.

``sulu:build dev`` and ``sulu:build prod`` are a shortcut for this: their ``search`` target creates the
missing indexes (see :doc:`../book/getting-started`). To run only that target, or to drop and recreate the
indexes, use:

.. code-block:: bash

    php bin/adminconsole sulu:build search
    php bin/adminconsole sulu:build search --destroy

Reindexing
----------

Reindexing reads all entities and rewrites their search documents. Run it after:

- an import of data,
- changing the template groups of articles (since Sulu 3.1 also of snippets), because the group decides the
  security context of a result and the view it opens,
- setting or removing the permissions of a single page, because the ``website`` index checks them only when
  the page is written to it.

After changing the schema of an index, add ``--drop``, because the command only creates missing indexes:

.. code-block:: bash

    php bin/console cmsig:seal:reindex --drop

The command is part of the SEAL bundle, see the `SEAL documentation`_ for its options. The providers of Sulu ignore
``--datetime-boundary`` and always reindex all documents. ``--identifiers`` takes the ids of the documents and needs
``--index``, for example ``--index=admin --identifiers=pages__<id>__en``.

Documents are updated automatically when content changes: the listeners of the bundles dispatch a
``CmsIg\Seal\Reindex\ReindexConfig`` message with the ids of the changed documents on the Symfony
Messenger bus, and the ``sulu_search.reindex_message_handler`` service handles it. Messenger handles
the message directly unless you route it to a transport.

A page or article is written to the ``website`` index when a workflow transition is applied to it, for
example when it is published, and it is removed from the index when it is unpublished or removed. The
``admin`` index is updated when the draft is created, saved, removed or restored, see :doc:`page/drafting`.
A copied page and a restored page version appear in it after the next reindex.

Configuration
-------------

The search of the administration interface lists the resources registered under
``sulu_search.admin.resources``. The key is the resource key of the documents. The Sulu bundles register
their resources in a `PrependExtension`_. For your own entity add:

.. code-block:: yaml

    # config/packages/sulu_search.yaml
    sulu_search:
        admin:
            resources:
                examples:
                    name: 'app.examples'
                    icon: 'su-document'
                    securityContext: 'app.examples'
                    route:
                        name: 'app.example_edit_form'
                        resultToRoute:
                            resourceId: id

- ``name``: Translation key of the resource name shown on each result.
- ``icon``: Icon of the resource shown on each result.
- ``securityContext``: Informational only, the search does not read it. The search filters by the
  ``securityContext`` field of the documents instead: a user finds a document if the user has the
  ``edit`` permission on the context in that field, which the reindex provider has to set.
- ``route.name``: The view of the administration interface opened on a click on a result.
- ``route.resultToRoute``: Maps values of the search result to the attributes of the route. The key is
  a `Jexl`_ expression evaluated on the result (for example ``resourceId`` or ``metadata.webspaceKey``),
  the value is the name of the route attribute.
- ``route.resultToRouteName``: Like ``resultToRoute``, but replaces a placeholder of ``route.name``. The
  articles use the route name ``sulu_article.article.edit_tabs_{group}`` and
  ``metadata.group: group`` to open the view of the group of the article.

Website Search
--------------

The skeleton imports the route of the website search:

.. code-block:: yaml

    # config/routes/sulu_website.yaml
    sulu_search:
        type: portal
        resource: "@SuluSearchBundle/config/routing_website.yaml"

It registers the route ``sulu_search.website_search`` on ``search.{_format}`` below the URL of the portal,
for example ``/en/search``. The ``_format`` is optional and defaults to ``html``. The controller searches
the ``website`` index for the query parameter ``q``, filtered by the locale and the webspace of the
current request, and renders the template of type ``search`` of the webspace. The response is a ``404``
if the request has no webspace or no localization, or the webspace has no such template.

.. code-block:: xml

    <!-- config/webspaces/website.xml -->
    <templates>
        <template type="search">search/search</template>
    </templates>

The template ``templates/search/search.html.twig`` receives the variables ``query`` (the search term)
and ``hits`` (the list of documents), next to the usual variables of a website template like
``request``. See :doc:`../book/webspaces` for more details on the templates of a webspace.

.. code-block:: twig

    {# templates/search/search.html.twig #}
    {% extends 'base.html.twig' %}

    {% block content %}
        <h2>{{ hits|length }} results for "{{ query }}"</h2>

        <ul>
            {% for hit in hits %}
                <li>
                    <a href="{{ sulu_content_path(hit.url) }}">{{ hit.title }}</a>
                </li>
            {% endfor %}
        </ul>
    {% endblock %}

A hit contains the fields of the ``website`` index: ``id``, ``resourceKey``, ``resourceId``, ``locale``,
``webspaces``, ``title``, ``url``, ``content``, ``mediaId``, ``authoredAt`` and ``metadata``. The
controller asks the engine to highlight ``title`` and ``content`` with ``<mark>`` tags. With the Loupe
adapter the highlighted title is in ``hit._formatted.title``, which is ``null`` when the title has no match.
``hit._formatted.content`` is always ``null``, because ``content`` is a list.

Link to the search with the route name:

.. code-block:: twig

    <form action="{{ path('sulu_search.website_search') }}" method="GET">
        <input name="q" type="text" placeholder="Search"/>
    </form>

What is indexed
^^^^^^^^^^^^^^^

The ``website`` index contains the live content of pages and articles. Pages that have a permission set
on the page itself are not part of it. The ``content`` of a document is built from:

- the properties of the template tagged with ``sulu.search.field``, see :ref:`templates-search`,
- the title and the description of the excerpt.

The ``title`` of a document is the property with the role ``title``, otherwise the title of the excerpt,
otherwise the title of the page or article. The excerpt is also stored in the ``metadata`` of the
document: ``metadata.excerpt.title``, ``description``, ``more``, ``imageId``, ``iconId``, ``categoryIds``
(the ids of the categories) and ``tagNames`` (the names of the tags). The image of the excerpt is used as
``mediaId`` if the template has no property with the role ``image``.

.. _SEAL: https://github.com/PHP-CMSIG/search
.. _SEAL documentation: https://php-cmsig.github.io/search/
.. _Loupe: https://github.com/loupe-php/loupe
.. _PrependExtension: https://symfony.com/doc/current/bundles/prepend_extension.html
.. _Jexl: https://github.com/TomFrost/Jexl
