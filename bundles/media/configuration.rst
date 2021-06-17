Configuration
=============

The SuluMediaBundle can be configured the following way:

.. code-block:: yaml

    # config/packages/sulu_media.yaml
    sulu_media:
        adapter: 'auto' # Can be set to a fixed adapter with 'gd', 'vips' or 'imagick'
        upload:
            max_filesize: 256 # Max upload filesize (in MB)
            blocked_file_types: # Will prevent the user from uploading files with those mime types
                - video/x-flv
                - video/mp4
                - video/MP2T
                - video/3gpp
                - video/quicktime
                - video/x-msvideo
                - video/x-ms-wmv
