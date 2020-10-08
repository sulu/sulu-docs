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
        </params>
    </property>

Twig
----

.. code-block:: twig

    {{ content.number }}
