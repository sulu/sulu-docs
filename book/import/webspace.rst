Webspace Import
===============

A whole webspace a specific site can be imported in the following formats.

======== ========= ============
 Format   Version  Key
======== ========= ============
 XLIFF    1.2       1.2.xliff
======== ========= ============


Parameters
----------

Import Command: ``sulu:webspace:import``

================ ================= ==================== ==================== ====================
 Name             Short             Values               required             Default
================ ================= ==================== ==================== ====================
 file                               export.xliff         true
 webspace                           webspacekey          true
 locale                             locale               true
 format           f                 format key           false                1.2.xliff
 uuid             u                 page uuid            false
 overrideSettings o                 override settings    false                false
================ ================= ==================== ==================== ====================

Example
-------
Import all documents to en from language export:
.. code-block::bask
    app/console sulu:webspaces:import export.xliff sulu_lo en

Import only one Document to en by given uuid from full export:
.. code-block:: bash
    app/console sulu:webspaces:import export.xliff sulu_lo en -u 6f7b92c1-81a3-424d-97a6-95728f217fa1
