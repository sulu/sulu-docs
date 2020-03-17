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
      - This condition is evaluated foreach record in the list and is used to determine if the record is selectable.
    * - allow_deselect_for_disabled_items
      - boolean
      - Defines if the user can deselect a selected item if `item_disabled_condition` is true.
    * - form_options_to_list_options
      - string
      - Defines from options which will be passed to the list options.

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
