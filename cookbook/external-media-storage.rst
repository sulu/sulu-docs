Store Media in an external Storage
==================================

Sulu is able to upload newly created media files directly to an external storage provider (such as
Google Cloud Storage).

Google Cloud-Storage
--------------------

First follow this the `Google Cloud Documentation`_ to setup a System-Account and download the json-key.

.. code-block:: json

    {
        "type": "service_account",
        "project_id": "project-id",
        "private_key_id": "some_number",
        "private_key": "-----BEGIN PRIVATE KEY-----\n....
        =\n-----END PRIVATE KEY-----\n",
        "client_email": "<api-name>api@project-id.iam.gserviceaccount.com",
        "client_id": "...",
        "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        "token_uri": "https://accounts.google.com/o/oauth2/token",
        "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        "client_x509_cert_url": "https://www.googleapis.com/...<api-name>api%40project-id.iam.gserviceaccount.com"
    }


Dump this file to a readable folder on your machine and configure the storage with following yaml-snippet:

.. code-block:: yaml

    sulu_media:
        storage: google_cloud
        storages:
            google_cloud:
                key_file_path: '/path/to/key.json'
                bucket_name: 'sulu-bucket'

.. _Google Cloud Documentation: https://cloud.google.com/video-intelligence/docs/common/auth#set_up_a_service_account
