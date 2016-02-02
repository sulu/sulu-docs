How to manage analytics?
========================

Sulu gives the content-manger an easy way to manage analytic-codes and appends
them automatically to the website output without any changes in the
twig-template. You can find the list of analytics under the webspace-settings.

The analytics consist of:

.. list-table::
    :header-rows: 1

    * - Title
      - To identify it.
    * - Domains
      - On which domain this analytics should be appended.
    * - All Domains
      - Should it appended to all domains.
    * - Type
      - The type (google, piwik, custom).
    * - Content
      - The code or key of the analytic.

Sulu can handle different types of analytic-systems like google or piwik.
This codes will be automatically added with the given key and site-id (for
piwik). To add other systems simply choose type custom and copy and paste
the code into the textarea.

.. warning::

    Be aware that custom analytics will not be evaluated and appended without
    validation - therefor it could break the website directly after saving.
