Snippet Import
===============

Import all snippet from xliff file.

======== ========= ============
 Format   Version  Key
======== ========= ============
 XLIFF    1.2       1.2.xliff
======== ========= ============

Parameters
----------

Import Command: ``sulu:snippet:import``

================ ================= ==================== ==================== ====================
 Name             Short             Values               required             Default
================ ================= ==================== ==================== ====================
 file                               path to file         true
 locale                             locale               true
 format           f                 format key           false                1.2.xliff
================ ================= ==================== ==================== ====================

Example
-------

Import all Snippets from `snippet_export_en.xliff`.
.. code-block:: bash
    app/console sulu:snippet:import snippet_export_en.xliff en
