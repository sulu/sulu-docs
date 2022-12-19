Store Media in an external Storage
==================================

Sulu is able to upload newly created media files directly to an external storage provider (such as AWS S3 or
Google Cloud Storage).

AWS-S3
------

First install dependencies.

.. code-block:: bash

    composer require "league/flysystem:^1.0" "league/flysystem-aws-s3-v3:^1.0.1"

Configure the storage with following yaml-snippet:

.. code-block:: yaml

    sulu_media:
        storage: s3
        storages:
            s3:
                key: 'your aws s3 key'
                secret: 'your aws s3 secret'
                bucket_name: 'your aws s3 bucket name'
                path_prefix: 'optional path prefix'
                region: 'eu-west-1'

If you use s3 compatible services (e.g. minio) you can pass additional ``arguments`` and ``endpoint`` to the
configuration.

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

Install the dependencies:

.. code-block:: bash

    composer require "league/flysystem:^1.0" "superbalist/flysystem-google-storage:^7.1"

Dump this file to a readable folder on your machine and configure the storage with following yaml-snippet:

.. code-block:: yaml

    sulu_media:
        storage: google_cloud
        storages:
            google_cloud:
                key_file_path: '/path/to/key.json'
                bucket_name: 'sulu-bucket'
                path_prefix: 'optional path prefix'

.. _Google Cloud Documentation: https://cloud.google.com/video-intelligence/docs/common/auth#set_up_a_service_account


Azure Blob Storage
------------------

First install dependencies.

.. code-block:: bash

    composer require "league/flysystem:^1.0" "league/flysystem-azure-blob-storage:^0.1"

Configure the storage with following yaml-snippet:

.. code-block:: yaml

    sulu_media:
        storage: azure_blob
        storages:
            azure_blob:
                connection_string: 'DefaultEndpointsProtocol=https;AccountName={YOUR_ACCOUNT_NAME};AccountKey={YOUR_ACCOUNT_KEY};'
                container_name: 'container-name'
                path_prefix: 'optional path prefix'

