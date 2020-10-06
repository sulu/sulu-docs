Checkbox
========

Description
-----------

Shows a simple checkbox, the value of the checkbox can be ``null`` (not changed yet and no default value), ``false`` or ``true``.

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
    * - default_value
      - bool
      - Defines the default value of the checkbox. When not set the initial value of the checkbox is null.
    * - label
      -
      - Defines the label of the checkbox. This is recommended if the meta title should be next to the toggler instead of above it.

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
            <param name="default_value" value="true"/>
        </params>
    </property>

.. code-block:: xml

    <property name="available" type="checkbox">
        <params>
            <param name="type" value="toggler"/>
            <param name="label">
                <meta>
                    <title lang="en">Show Author</title>
                </meta>
            </param>
        </params>
    </property>

Twig
----

.. code-block:: twig

    {% if content.available %}
        {# ... #}
    {% endif %}
