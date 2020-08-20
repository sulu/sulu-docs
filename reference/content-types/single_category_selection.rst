Single Category selection
=========================

Description
-----------

Let you assign one category. Categories can be managed in the settings section of Sulu.
The selection will be saved as a single id.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - request_parameters
      - collection
      - Collection of parameters that are appended to the requests sent by the selection.
    * - item_disabled_condition
      - string
      - Allows to set a `jexl`_ expression that evaluates if an item should be displayed as disabled.
        Disabled items cannot be selected.
    * - allow_deselect_for_disabled_items
      - bool
      - Defines if the user should be able to deselect an item that is disabled. Default value is true.

Example
-------

.. code-block:: xml

    <property name="category" type="single_category_selection">
        <meta>
            <title lang="en">Single Category Selection</title>
        </meta>
    </property>

Extended Example
----------------

Following example defines an entry category for the selection tree.

.. code-block:: xml

    <property name="category" type="single_category_selection">
        <meta>
            <title lang="en">Single Category Selection</title>
        </meta>

        <params>
            <param name="request_parameters" type="collection">
                <param name="rootKey" value="my_category_key"/>
            </param>
        </params>
    </property>

.. _jexl: https://github.com/TomFrost/jexl
