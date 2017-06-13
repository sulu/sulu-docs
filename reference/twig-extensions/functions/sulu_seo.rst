``sulu_seo``
============

.. note::

    This method is deprecated. Use the predefined template as described in
    :doc:`../../../book/twig`.

Returns all the SEO related HTML tags as one string, including:

- Title
- Description
- Keywords
- Alternate links
- Canonical tag

.. code-block:: jinja

    {{ sulu_seo(extension.seo, content, urls, shadowBaseLocale) }}

**Arguments**:

- **extension**: *array* - The values of the SEO extension
- **content**: *array* - The values of the actual content
- **urls**: *array* - All urls in all localizations for this page
- **shadowBaseLocale**: *string* - The locale the page shadows to, in case the
  page is a shadow page

**Returns**:

All HTML strings as a simple string

