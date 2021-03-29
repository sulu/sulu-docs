Media Properties Provider
=========================

Media properties providers are used to gather data for a given file. Sulu will
save the provided properties to the respective FileVersion entity which can
be accessed in twig templates and PHP code. 

Sulu is shipped with the following providers:

Image Properties Provider
-------------------------

The ``ImagePropertiesProvider`` works for every image which is supported by the 
configured :doc:`imagine adapter <imagine-adapter>` and provides the ``width`` and 
``height`` of an image.
This information can be used in CSS to provide placeholder images or other kind of things.

Video Properties Provider
-------------------------

The ``VideoPropertiesProvider`` requires that ``ffprobe`` is configured and installed
on your server. When it is available the provider will return the ``duration``,
``width`` and ``height`` of the video.

Create custom Properties Provider
---------------------------------

It is possible to register a custom media properties provider in your project.
To do this, you create a new service that implements the ``PropertiesProviderInterface``:

.. code-block:: php

    <?php

    namespace App\Media\PropertiesProvider;

    use Sulu\Bundle\MediaBundle\Media\PropertiesProvider\MediaPropertiesProviderInterface;
    use Symfony\Component\HttpFoundation\File\File;

    class ExifPropertiesProvider implements MediaPropertiesProviderInterface
    {
        public function provide(File $file): array
        {
            $mimeType = $file->getMimeType();

            if (!$mimeType || !\fnmatch('image/*', $mimeType)) {
                return [];
            }

            $properties = [];

            $exifData = exif_read_data($file->getPathname(), 'EXIF');

            if (isset($exifData['EXIF_HEADER_NAME'])) {
                $properties['exif_header_name'] = $exifData['EXIF_HEADER_NAME'];
            }

            return $properties;
        }
    }

When ``autoconfigure`` is disabled you need to make sure that you tag the service
with the ``sulu_media.media_properties_provider`` tag.

.. code-block:: yaml

    # config/services.yaml
    App\Media\PropertiesProvider\ExifPropertiesProvider:
        tags:
            - { name: 'sulu_media.media_properties_provider' }

After this, the service should be listed when executing the following command:

.. code-block:: bash

    php bin/adminconsole debug:container --tag sulu_media.media_properties_provider
