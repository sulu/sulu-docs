Media Properties Provider
=========================

The media properties provider will provide data for a given file. Sulu will
use the provided data to save it to the Media FileVersion object so a developer
can access it in there twig templates or apis.

Sulu is shipped with the following providers:

Image Properties Provider
-------------------------

The ``ImagePropertiesProvider`` works for every image which is supported by your
used :doc:`imagine adapter <imagine-adapter>`.
It will provide the ``width`` and ``height`` of a image
this can be used in CSS to provide placeholder images or other kind of things.

Video Properties Provider
-------------------------

The ``VideoPropertiesProvider`` requires that ``ffprobe`` is configured and installed
on your server. When it is available the provider will return the ``duration``,
``width`` and ``height`` of the video.

Create own one Properties Provider
----------------------------------

Its possible to create your   own media properties provider you need to create a new
service which implements the ``PropertiesProviderInterface`` like the following:

.. code-block:: php

    <?php

    namespace App\Media\PropertiesProvider;

    use Symfony\Component\HttpFoundation\File\File;

    class ExifPropertiesProvider implements PropertiesProviderInterface
    {
        public function provide(File $file): array
        {
            $properties = [];

            $exifData = exif_read_data($file->getPathname(), 'EXIF');

            if (isset($exifData['EXIF_HEADER_NAME'])) {
                $properties['exif_header_name'] = $exifData['EXIF_HEADER_NAME'];
            }

            return $properties;
        }

        public static function supports(File $file): bool
        {
            return \fnmatch('image/*', $file->getMimeType());
        }
    }

When ``autoconfigure`` is disabled you need to make sure that you tag the service
with the ``sulu_media.media_properties_provider`` tag.

.. code-block:: yaml

    # config/services.yaml
    App\Media\PropertiesProvider\ExifPropertiesProvider:
        tags:
            - { name: 'sulu_media.media_properties_provider' }

After this the service should be listed here:

.. code-block:: bash

    php bin/adminconsole debug:container --tag sulu_media.media_properties_provider
