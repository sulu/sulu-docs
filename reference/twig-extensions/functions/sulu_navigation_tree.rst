``sulu_navigation_tree``
========================

Returns navigation from the given page in a tree data-structure.

**Arguments**:

- **uuid**: *string* The uuid for which the navigation should be loaded
- **context**: *string* - optional: context to filter navigation
- **depth**: *integer* - optional: depth to load (1 - one level deep, 2 - two levels deep, ...)
- **loadExcerpt**: *boolean* - optional: load data from excerpt tab

**Returns**:

.. include:: _navigation_structure.inc

