Number
======

Description
-----------

Shows a number input field, the inserted content will be saved as number.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - min
      - number
      - The minimum number that can be entered
    * - max
      - number
      - The maximum number that can be entered
    * - step
      - number
      - The allowed steps between minium and maximum number
    * - multiple_of
      - number
      - If set, the input value needs to be a multiple of this parameter. Setting this to ``1`` ensures, that the inputted value is an integer.

Example
-------

.. code-block:: xml

    <property name="number" type="number">
        <meta>
            <title lang="en">Number</title>
        </meta>

        <params>
            <param name="min" value="0"/>
            <param name="max" value="100"/>
            <param name="step" value="2"/>
            <param name="multiple_of" value="1"/>
        </params>
    </property>

Twig
----

.. code-block:: twig

    {{ content.number }}
