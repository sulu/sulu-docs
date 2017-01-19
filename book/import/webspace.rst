Webspace Import
===============

A whole webspace or a specific site can be imported in the following formats.

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
 webspace         w                 webspacekey          true
 locale           l                 locale               true
 format           f                 format key           false                1.2.xliff
 uuid             u                 page uuid            false
 overrideSettings o                 override settings    false                false
================ ================= ==================== ==================== ====================

Example
-------

Import one Document from `export_en.xliff` to webspace sulu_io.
.. code-block:: bash

    app/console sulu:webspaces:import export_en.xliff -w sulu_lo -l en -o true -u 6f7b92c1-81a3-424d-97a6-95728f217fa1

Import all Documents from `export_en.xliff` to webspace sulu_io
.. code-block:: bash

    app/console sulu:webspaces:import export_en.xliff -w sulu_lo -l en
