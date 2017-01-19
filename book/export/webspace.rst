Webspace Export
===============

Export a whole webspace from given language to a xliff-file.

Parameters
----------

Export Command: ``sulu:webspace:export``

================ ================= ==================== ====================
 Name             Short             Values               required
================ ================= ==================== ====================
 target                             filename             true
 webspace                           webspaceKey          true
 locale                             locale               true
================ ================= ==================== ====================

Example
-------

Export all Snippets from language en to file `snippet_export_en.xliff`.
.. code-block:: bash
    app/console sulu:webspace:export webspace_export.xliff sulu_io en
