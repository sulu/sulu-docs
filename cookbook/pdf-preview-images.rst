Generating thumbnails for pdf files with ghostscript
====================================================

Ghostscript (gs) is a PostScript and PDF language interpreter and previewer.
Sulu is able to use the **gs** commandline program to generate thumbnail/preview images for pdf files.
The location of the **gs** program depends on your system and needs to be configurated for Sulu to find it.

Add configuration `config/packages/sulu_media.yml`:

.. code-block:: yaml

    sulu_media:
        ghost_script: /usr/bin/gs

Common locations are:

* /usr/bin/gs
* /usr/local/bin/gs

You can also try to use the ``which gs`` command to get the location.
