Versioning
==========

The versioning feature in Sulu creates a new version each time a page is
published. Version history is available in the settings tab of each page,
allowing you to restore previous versions.

How Versioning Works
--------------------

Sulu's multi-dimension entity system natively supports versioning:

- **Version 0**: The current working version (always editable)
- **Version 1+**: Historical snapshots created during publishing

When you publish a page:

1. The current draft content (Version 0) is copied to the live stage
2. Optionally, a new historical version is created with a timestamp
3. The version is stored with all dimension data (locale, stage, content)

Restoring Versions
------------------

When you restore an old version from the version history table:

1. The selected version's content is copied to Version 0 (draft stage)
2. This creates a new draft based on the historical version
3. You can review and edit the restored content before publishing
4. Publishing creates a new version in the history

Version Storage
---------------

Each version is stored as a separate dimension entity containing:

- Version number
- Locale
- Stage (draft/live)
- Content data (JSON)
- Timestamp metadata

Managing Versions
-----------------

Versions are managed automatically by Sulu. No additional configuration
is required - versioning is built into the core dimension system.

The version history table in the admin interface shows:

- Version number
- Publication date
- Editor who created the version
- Actions (restore, compare)