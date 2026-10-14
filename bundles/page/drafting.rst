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

Sulu keeps the draft and the live content of pages in two search indexes (see :doc:`../search`):

- The ``admin`` index contains the draft content. It is updated when a page or one of its translations is created,
  saved, removed or restored from the trash, and is used by the search of the administration interface.
  A copied page and a restored version appear in it after the next reindex.
- The ``website`` index contains the live content. It is updated when a workflow transition like
  publishing is applied to the page, and the page is removed from it when it is unpublished or removed.
  It is used by the website search.

To rebuild both indexes from the stored content, run:

.. code-block:: bash

    php bin/console cmsig:seal:reindex

Content Dimensions
------------------

The dimension system allows content to exist in multiple states simultaneously:

- A page can have different content per locale (e.g., English vs German)
- Each locale can have separate draft and live versions
- Historical versions can be maintained for auditing and restoration

This multi-dimensional approach keeps complexity hidden from end users while
providing powerful content management capabilities for editors and administrators.