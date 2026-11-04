Generating thumbnails for pdf files with ghostscript
====================================================

Ghostscript (gs) is a PostScript and PDF language interpreter and previewer.
Sulu is able to use the **gs** commandline program to generate thumbnail/preview images for pdf files.
The location of the **gs** program depends on your system and needs to be configurated for Sulu to find it.

Add configuration `config/packages/sulu_media.yml`:

.. code-block:: yaml

    parameters:
        # Sulu by default assumes that the gs command is available in the PATH. This may not true for your WebServer user:
        env(GHOST_SCRIPT_PATH): 'gs'

    sulu_media:
        ghost_script:
            path: '%env(GHOST_SCRIPT_PATH)%'

Common locations are:

* gs
* /usr/bin/gs
* /usr/local/bin/gs

You can also try to use the ``which gs`` command to get the location.

Troubleshooting
---------------

The detection of ghost script is only done once when the Sulu application container is built in the ``SuluMediaExtension``.
If you change the location of the **gs** program, you need to clear the cache of Sulu to make it detect the new location.

You can debug the detection of ghostscript by running the following command:

.. code-block:: bash

    php bin/console debug:container --parameter=sulu_media.format_manager.mime_types --format=json

If configured correctly, you should see the following output:

.. code-block:: json

    {
        "sulu_media.format_manager.mime_types": [
            "image\/*",
            "application\/pdf"
        ]
    }

When this is configured Sulu ``MediaImageExtractor`` will use the **gs** command to generate a thumbnail for pdf files.
