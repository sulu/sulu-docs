DataProvider for SmartContent
=============================

DataProviders are used to load data for SmartContent. It returns data filtered
by a configuration array. This array can be configured with an overlay in the
backend form.

This configuration array includes following values:

.. list-table::
    :header-rows: 1

    * - Name
      - Description
    * - dataSource
      - Additional constraint - like page-"folder".
    * - tags
      - Multiple selection of tags, which a item should have.
    * - tagOperator
      - The item has any or all of the selected tags.

Tags (websiteTags) can also be "injected" by GET parameters from the website.
This can be handled separately from the admin-selected.

Additional features, which can be provider with a DataProvider:

.. list-table::
    :header-rows: 1

    * - Name
      - Description
    * - presentAs
      - Value can be used in the website for display options - like one or two
        column - these values can be freely configured by developers.
    * - page & pageSize
      - Pagination of items.
    * - limit
      - Maximum items for (if pagination is active) over all pages or overall.

How to create a custom DataProvider?
------------------------------------

To create a custom data provider you simply have to create a service which
implements the Interface `DataProviderInterface`. This Interface provides
functions to resolve the configured filters for the backend API with standardized
objects and for the website with array and entity access. Additionally the
DataProvider returns a configuration object to enable or disable features.

For example how to implement it for ORM based entities see the Pull-Request for
the `Account DataProvider`_.

.. _Account DataProvider: https://github.com/sulu-io/sulu/pull/1517
