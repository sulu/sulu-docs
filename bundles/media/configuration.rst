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

Origin and AI disclosure
------------------------

Available since Sulu 2.6.26 and 3.0.9. The media details have an "Origin" field with the values "Created by humans"
(``human_created``), "Created by AI" (``ai_generated``), "Modified by AI" (``ai_modified``) and "Unknown"
(``unknown``, the default). "Created by AI" and "Modified by AI" show an "AI Disclosure" section:

.. list-table::
    :header-rows: 1

    * - Field
      - Description
    * - Disable AI Hint
      - Confirms that the media file needs no label under the EU AI Act. Hides the two fields below.
    * - AI hint text
      - The text of the hint, set per locale.
    * - Icon variant
      - ``auto``, ``light`` or ``dark``.

The values are stored per file version in the ``origin``, ``aiDisclosureDisabled`` and ``aiDisclosureIconVariant``
columns of ``me_file_versions``, and in the ``aiDisclosureText`` column of ``me_file_version_meta``. The media API
returns them under the same names. Update the schema of your database after updating, with the
``doctrine:schema:update`` or the ``doctrine:migrations:diff`` command.

Sulu shows no hint on the website. Templates read ``origin``, ``aiDisclosureDisabled``, ``aiDisclosureText`` and
``aiDisclosureIconVariant`` from the media object. Label images are in ``/bundles/sulumedia/images/ai-label/``.
