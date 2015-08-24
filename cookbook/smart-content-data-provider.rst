DataProvider for SmartContent
=============================

DataProviders are used to load data for SmartContent. It returns
data filtered by a configuration array. This array can be
configured with an overlay in the backend form.

This filters includes following values:

.. list-table::
    :header-rows: 1

    * - Name
      - Description
    * - DataSource
      - Additional constraint - like page-"folder"
    * - Tags
      - Multiple selection of tags, which a item should have - all or one of them
    * - Categories (coming soon)
      - Multiple selection of categories, which a item should have - all or one of them

Additional feature which can be provider with a DataProvider:

.. list-table::
    :header-rows: 1

    * - Name
      - Description
    * - PresentAs
      - Value will be used in the website for display options - like one or two column
    * - Pagination
      - Pagination of items
    * - Limitation
      - Maximum items for (if pagination is active) over all pages or overall

How to create a custom DataProvider?
------------------------------------
To create a custom data provider you simply have to create a
service which implements the Interface `DataProviderInterface`.
This Interface provides function to resolve the configured
filters for the backend API with standardized objects and website
for array and entity access. Additionally the DaraProvider
returns a configuration object to enable or disable features.

For example how to implement it for ORM based entities see the
Pull-Request for the `Account DataProvider`_.

.. _Account DataProvider: https://github.com/sulu-io/sulu/pull/1517
