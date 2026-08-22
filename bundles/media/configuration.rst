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

Limiting the parallel image generation
--------------------------------------

Image formats are generated on the fly the first time they are requested. When
many uncached formats are requested at once (e.g. when a large media collection
is opened in the administration interface for the first time), every PHP worker
generates an image at the same time, which can exhaust the memory of the
server. The ``parallel_image_generation.limit`` option limits the number of
HTTP requests generating an image concurrently; the other requests wait for a
free slot before their image is generated:

.. code-block:: yaml

    # config/packages/sulu_media.yaml
    sulu_media:
        format_manager:
            parallel_image_generation:
                limit: 4

The limit is shared between all the PHP workers of the application through a
semaphore of the `Symfony Semaphore component`_, using the storage configured
under ``framework.semaphore`` (e.g. a Redis DSN):

.. code-block:: bash

    composer require symfony/semaphore

.. code-block:: yaml

    # config/packages/semaphore.yaml
    framework:
        semaphore: 'redis://localhost'

When no slot becomes available within one minute, the request fails: the limit
protects the server, generating the image anyway would defeat it.

.. _`Symfony Semaphore component`: https://symfony.com/doc/current/components/semaphore.html
