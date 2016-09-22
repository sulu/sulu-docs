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
      - Defines the look of the checkbox, can either be "checkbox" (default) or "toggler"

Example
-------

.. code-block:: xml

    <property name="available" type="checkbox">
        <meta>
            <title lang="en">Available</title>
        </meta>
        
        <params>
            <param name="type" value="toggler"/>
        </params>
    </property>
