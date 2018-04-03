Generating thumbnails for video files with ffmpeg
=================================================

FFmpeg is a library to process video files. Sulu is able to use this libraries
to generate thumbnail images for video files.

1. Install ffmpeg-bundle:

.. code-block:: bash

    composer require pulse00/ffmpeg-bundle

2. Add bundle to `app/AbstractKernel.php`:

.. code-block:: php

     abstract class AbstractKernel extends SuluKernel
     {
         public function registerBundles()
         {
             $bundles = [
                 ...

                 new Dubture\FFmpegBundle\DubtureFFmpegBundle(),
             ];

             return $bundles;
        }

        ...
    }

3. Add configuration `app/config/config.yml`:

.. code-block:: yaml

    dubture_f_fmpeg:
        ffmpeg_binary: /usr/local/bin/ffmpeg # path to ffmpeg
        ffprobe_binary: /usr/local/bin/ffprobe # path to ffprobe
        binary_timeout: 300 # Use 0 for infinite
        threads_count: 4
