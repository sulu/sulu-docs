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

Available since Sulu 3.1. Image formats are generated on the fly the first time
they are requested. When many uncached formats are requested at once (e.g. when
a large media collection is opened in the administration interface for the first
time), every PHP worker generates an image at the same time, which can exhaust
the memory of the server. The ``parallel_image_generation.limit`` option limits
the number of HTTP requests generating an image concurrently; the other requests
wait for a free slot before their image is generated:

.. code-block:: yaml

    # config/packages/sulu_media.yaml
    sulu_media:
        format_manager:
            parallel_image_generation:
                limit: 4

The limit is shared between all the PHP workers of the application through a
semaphore of the `Symfony Semaphore component`_, using the storage configured
under ``framework.semaphore``. The ``lock://`` storage works out of the box,
without any extra service:

.. code-block:: bash

    composer require symfony/semaphore symfony/lock

.. code-block:: yaml

    # config/packages/lock.yaml
    framework:
        lock: '%env(LOCK_DSN)%'

    # config/packages/semaphore.yaml
    framework:
        semaphore: 'lock://'

The ``lock://`` storage requires ``symfony/semaphore`` 8.1. On older versions,
configure a Redis DSN instead (``semaphore: 'redis://localhost'``).

When no slot becomes available within 20 seconds, the request fails with a
``503 Service Unavailable`` response: the limit protects the server, generating
the image anyway would defeat it. A slot that is never released, e.g. because
the PHP worker holding it was killed, is freed automatically after one minute.

.. _`Symfony Semaphore component`: https://symfony.com/doc/current/components/semaphore.html

Media languages
---------------

Available since Sulu 3.1. Documents and videos have a "Media language" field,
which records the language(s) the file itself is in, independent of the content
locale (e.g. a French datasheet placed on a German page). The field is optional,
accepts multiple languages and is kept when a new version of the file is
uploaded. The media overview can be filtered by it, including media without any
language.

By default, the selectable languages are the locales of the webspaces. The
``media_languages`` option replaces them with a fixed list of language codes:

.. code-block:: yaml

    # config/packages/sulu_media.yaml
    sulu_media:
        media_languages: ['de', 'en', 'fr', 'it']

The administration interface shows each code with its localized name (``de_at``
becomes "German (Austria)"). The stored codes are available in the media data
of the API and in Twig:

.. code-block:: twig

    {{ media.mediaLanguages|join(', ') }}
