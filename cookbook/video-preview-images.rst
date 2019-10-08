Generating thumbnails for video files with ffmpeg
=================================================

FFmpeg is a library to process video files. Sulu is able to use this libraries
to generate thumbnail images for video files.

1. Install ffmpeg-bundle:

.. code-block:: bash

    composer require php-ffmpeg/php-ffmpeg

2. Add configuration `config/packages/sulu_media.yml`:

.. code-block:: yaml

    sulu_media:
        ffmpeg:
            ffmpeg_binary: /usr/local/bin/ffmpeg # path to ffmpeg
            ffprobe_binary: /usr/local/bin/ffprobe # path to ffprobe
