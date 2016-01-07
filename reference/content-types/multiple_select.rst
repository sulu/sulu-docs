Multiple Select
===============

Description
-----------

Adds the possibility to choose multiple values from a given list of values.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - values
      - collection
      - A collection of values to choose from.

Example
-------

.. code-block:: xml

    <property name="single" type="multiple_select">
        <meta>
            <title lang="en">Multiple Select</title>
        </meta>
        <params>
            <param name="values" type="collection">
                <param name="option1">
                    <meta>
                        <title lang="en">Option 1</title>
                    </meta>
                </param>
                <param name="option2">
                    <meta>
                        <title lang="en">Option 2</title>
                    </meta>
                </param>
            </param>
        </params>
    </property>
