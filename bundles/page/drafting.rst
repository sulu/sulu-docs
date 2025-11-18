Drafting
========

The drafting feature of Sulu allows you to work on a draft version of a page
without making changes visible on the live website. Pages must be published
to make them available to website visitors.

Multi-Dimension Content Storage
--------------------------------

Sulu uses a modern multi-dimension entity system for content storage.
This system supports multiple dimensions:

- **Locale**: Different languages and localizations
- **Stage**: Draft vs Live content
- **Version**: Historical versions of content

Technical Structure
-------------------

Content is stored using dimension entities that contain:

- A unique identifier
- Locale information
- Stage indicator (draft or live)
- Version number
- Content data (stored as JSON)

Draft and Live Workflow
------------------------

When working with pages in Sulu:

1. **Editing**: All edits are made to the draft stage (version 0)
2. **Publishing**: Publishing copies content from draft to live stage
3. **Website Display**: Only live stage content is displayed on the website

Search Indexing
---------------

Sulu maintains separate search indexes for draft and live content:

- Draft index: ``sulu_page_<webspace>-<locale>-i18n``
- Live index: ``sulu_page_<webspace>-<locale>-i18n_published``

To reindex content for both draft and live:

.. code-block:: bash

    php bin/console massive:search:reindex # Reindex draft content
    php bin/websiteconsole massive:search:reindex # Reindex live content

Content Dimensions
------------------

The dimension system allows content to exist in multiple states simultaneously:

- A page can have different content per locale (e.g., English vs German)
- Each locale can have separate draft and live versions
- Historical versions can be maintained for auditing and restoration

This multi-dimensional approach keeps complexity hidden from end users while
providing powerful content management capabilities for editors and administrators.