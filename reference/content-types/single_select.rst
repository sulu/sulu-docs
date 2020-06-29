Single Select
=============

Description
-----------

Adds the possibility to choose a single value from a given list of values.

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
    * - default_value
      - string
      - The name of the param which should be set as default.

Example
-------

.. code-block:: xml

    <property name="single" type="single_select">
        <meta>
            <title lang="en">Single Select</title>
        </meta>

        <params>
            <param name="default_value" value="option1"/>

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

You can use symfony expression language to access values or set a default value from a service.

.. code-block:: xml

    <property name="list" type="select">
        <meta>
            <title lang="en">Select</title>
        </meta>

        <params>
            <param name="default_value" type="expression" value="service('your_service').getDefaultValue()"/>
            <param name="values" type="expression" value="service('your_service').getValues()"/>
        </params>
    </property>
