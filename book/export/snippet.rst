Snippet Export
===============

Export all snippets from given language to a xliff-file.

Parameters
----------

Export Command: ``sulu:snippet:export``

================ ================= ==================== ====================
 Name             Short             Values               required
================ ================= ==================== ====================
 target                             filename             true
 locale                             locale               true
================ ================= ==================== ====================

Example
-------

Export all Snippets from language en to file `snippet_export_en.xliff`.
.. code-block:: bash
    app/console sulu:snippet:export snippet_export_en.xliff en
