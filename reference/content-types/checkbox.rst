Checkbox
========

Description
-----------

Shows a simple checkbox, the state of the checkbox will be saved as a boolean.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - type
      - string
      - Defines the look of the checkbox, can either be "checkbox" or "toggler". Be aware of the difference between property type and parameter type.

Examples
--------

.. code-block:: xml

    <property name="available" type="checkbox">
        <meta>
            <title lang="en">Available</title>
        </meta>
    </property>

.. code-block:: xml

    <property name="available" type="checkbox">
        <meta>
            <title lang="en">Show Author</title>
        </meta>
        <params>
            <param name="type" value="toggler"/>
        </params>
    </property>
