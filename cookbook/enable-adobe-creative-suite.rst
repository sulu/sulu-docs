Enable the adobe creative suite
===============================

Sulu supports the usage of the image editor provided by `Adobe Creative SDK`_.
Enabling the image editor is a two step process, since the SDK requires an API
key to run inside your application.

.. note::

    Be aware that the images are sent to the adobe servers for editing if you
    use the adobe creative suite.

Getting the API key
-------------------

Use the `My Apps`_ page to register your own application. If you don't have an
account yet create one. Afterwards create an application for the web platform.
Follow the instructions and copy the API key which will be shown at the end.

Configure Sulu to use the API key
---------------------------------

After retrieving the API key you have to configure Sulu to use that API key.
Therefore you have to set the ``sulu_media.adobe_create_key`` configuration
option. You can do that e.g. in the ``app/config/admin/config.yml`` file.

.. code-block:: yaml

    sulu_media:
        adobe_creative_key: your-api-key

Afterwards there should be another option available in the media edit overlay
called "Edit original image".

.. _Adobe Creative SDK: https://creativesdk.adobe.com/features/#/creative-tools.html
.. _My Apps: https://creativesdk.adobe.com/myapps.html
