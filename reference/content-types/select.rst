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

Expression Example
------------------

By using a ``param`` with the type ``expression``, it is also possible to display
the values returned by a service (:doc:`../../cookbook/select-values-service`).

.. note::

    Be aware that the provided expression is only evaluated during the initial request to the administration interface.
    If you want to provide a selection for your custom entity, you should configure the ``selection`` field-type
    as described in :doc:`../../book/extend-admin`.

.. code-block:: xml

    <property name="list" type="select">
        <meta>
            <title lang="en">Select</title>
        </meta>

        <params>
            <param name="default_values" type="expression" value="service('App\\Content\\Select\\EventTypeSelect').getDefaultValue()"/>
            <param name="values" type="expression" value="service('App\\Content\\Select\\EventTypeSelect').getValues(locale)"/>
        </params>
    </property>

Twig
----

.. code-block:: twig

    {% for item in content.list %}
        <span class="icon-{{ item }}">
            Icon {{ item }}
        </span>
    {% endfor %}
