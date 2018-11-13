Select
======

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
    * - default_values
      - collection
      - A collection of default values which are activated by default.

Example
-------

.. code-block:: xml

    <property name="list" type="select">
        <meta>
            <title lang="en">Select</title>
        </meta>

        <params>
            <param name="default_values" type="collection">
                <param name="option1"/>
                <param name="option2"/>
            </param>

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

                <param name="option3">
                    <meta>
                        <title lang="en">Option 3</title>
                    </meta>
                </param>
            </param>
        </params>
    </property>

You can use symfony expression language to access values from a service.

.. code-block:: xml

    <property name="list" type="select">
        <meta>
            <title lang="en">Select</title>
        </meta>

        <params>
            <param name="values" expression="service('your_service').getValues()"/>
        </params>
    </property>
