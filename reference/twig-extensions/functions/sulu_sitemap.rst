``sulu_sitemap``
================

Returns the URLs of all registered :doc:`sitemap providers </cookbook/sitemap-provider>` for the host of
the current request. This is the same data source the XML sitemap (``/sitemap.xml``) is rendered from, so
a human readable sitemap contains pages, articles and every other routable entity which registers a
sitemap provider, and both sitemaps can not drift apart.

Content which has *Hide in sitemap* enabled in its SEO settings is excluded, the same way it is excluded
from the XML sitemap.

**Example**:

.. code-block:: twig

    <ul>
        {% for entry in sulu_sitemap() %}
            <li>
                <a href="{{ entry.loc }}">{{ entry.title|default(entry.loc) }}</a>
            </li>
        {% endfor %}
    </ul>

The ``loc`` of an entry is already an absolute URL, therefore
:doc:`sulu_sitemap_url </reference/twig-extensions/functions/sulu_sitemap_url>` is not needed here.

**Grouped Example**:

Pass the alias of a single provider to render one section per content type. The available aliases can be
read with :doc:`sulu_sitemap_aliases </reference/twig-extensions/functions/sulu_sitemap_aliases>`.

.. code-block:: twig

    {% for alias in sulu_sitemap_aliases() %}
        <h2>{{ ('sitemap.' ~ alias)|trans }}</h2>

        <ul>
            {% for entry in sulu_sitemap(alias: alias) %}
                <li>
                    <a href="{{ entry.loc }}">{{ entry.title|default(entry.loc) }}</a>

                    {% if entry.lastmod %}
                        <time datetime="{{ entry.lastmod|date('c') }}">{{ entry.lastmod|date('d.m.Y') }}</time>
                    {% endif %}
                </li>
            {% endfor %}
        </ul>
    {% endfor %}

**Arguments**:

- **locale**: *string|null* - optional: locale to filter the URLs by. Defaults to the locale of the
  current request, ``null`` returns the URLs of all locales of the host.
- **alias**: *string|null* - optional: alias of a single sitemap provider, e.g. ``pages`` or ``articles``.
  Defaults to ``null``, which collects the URLs of all providers.
- **page**: *int* - optional: page of the sitemap providers. Providers are paginated with 50,000 URLs per
  page (``SitemapProviderInterface::PAGE_SIZE``). Defaults to ``1``.

**Returns**:

An array of ``Sulu\Bundle\WebsiteBundle\Sitemap\SitemapUrl`` objects with the following properties:

- **loc**: *string* - absolute URL of the entry.
- **title**: *string|null* - human readable title. Set by the page and article providers,
  :doc:`custom providers </cookbook/sitemap-provider>` may leave it empty.
- **locale**: *string* - locale of the entry.
- **defaultLocale**: *string|null* - default locale of the webspace.
- **lastmod**: *\\DateTimeInterface|null* - datetime of the last modification.
- **changefreq**: *string|null* - frequency of change.
- **priority**: *float|null* - priority relative to the other URLs.
- **alternateLinks**: *array* - alternate links keyed by locale, each with ``href`` and ``locale``.
- **attributes**: *array* - additional attributes provided by the sitemap provider.

.. note::

    Collecting the URLs queries every registered provider, so avoid calling this function in a partial
    which is rendered on every page. Repeated calls with the same arguments are served from an in-memory
    cache which lives for the duration of a request. Its lifetime can be configured:

    .. code-block:: yaml

        # config/packages/sulu_website.yaml
        sulu_website:
            twig:
                sitemap:
                    cache_lifetime: 3600
