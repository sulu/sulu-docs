``sulu_sitemap_url``
====================

Returns the absolute URL for a given slug, including scheme and domain. Use it when you have a bare slug
and need a URL which is also valid outside of the current request, for example in a sitemap, a feed or an
email. Within the website
:doc:`sulu_content_path </reference/twig-extensions/functions/sulu_content_path>` is usually the better
choice, because it returns a relative path.

The entries returned by :doc:`sulu_sitemap </reference/twig-extensions/functions/sulu_sitemap>` already
contain an absolute ``loc``, so this function is not needed for them.

**Example**:

.. code-block:: twig

    <a href="{{ sulu_sitemap_url('/products') }}">{{ 'products'|trans }}</a>

    {# a slug of another webspace and locale #}
    <a href="{{ sulu_sitemap_url('/products', 'en', 'other_io') }}">{{ 'products'|trans }}</a>

**Arguments**:

- **slug**: *string|null* - slug to resolve, e.g. ``/products``
- **locale**: *string|null* - optional: locale to resolve the URL for. Defaults to the locale of the
  current request.
- **webspaceKey**: *string|null* - optional: webspace to resolve the URL for. Defaults to the webspace of
  the current request.

**Returns**: *string|null* - absolute URL, or ``null`` if no URL is configured for the given webspace and
locale
