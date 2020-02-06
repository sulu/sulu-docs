Category selection
==================

Description
-----------

Shows a list of all available categories. The user can select with a checkbox
which ones to assign to the page. Categories can be managed in the settings
section of Sulu. The selection will be saved as an array.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - item_disabled_condition
      - string
      - Allows to set a `jexl`_ expression that evaluates if an item should be displayed as disabled.
        Disabled items cannot be selected.
    * - request_parameters
      - collection
      - Collection of parameters that are appended to the requests sent by the selection.
    * - resource_store_properties_to_request
      - collection
      - Collection of property names.
        The value of the respective properties are appended to the requests sent by the selection.


Example
-------

.. code-block:: xml

    <property name="categories" type="category_selection">
        <meta>
            <title lang="en">Category Selection</title>
        </meta>
    </property>

.. _jexl: https://github.com/TomFrost/jexl
