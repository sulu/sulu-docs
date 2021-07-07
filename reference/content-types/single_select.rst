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

Expression Example
------------------

By using a ``param`` with the type ``expression``, it is also possible to display
the values returned by a service (:doc:`../../cookbook/select-values-service`).

.. note::

    Be aware that the provided expression is only evaluated during the initial request to the administration interface.
    If you want to provide a selection for your custom entity, you should configure the ``single_selection`` field-type
    as described in :doc:`../../book/extend-admin`.

.. code-block:: xml

    <property name="single" type="single_select">
        <meta>
            <title lang="en">Single Select</title>
        </meta>

        <params>
            <param name="default_value" type="expression" value="service('App\\Content\\Select\\EventTypeSelect').getDefaultValue()"/>
            <param name="values" type="expression" value="service('App\\Content\\Select\\EventTypeSelect').getValues(locale)"/>
        </params>
    </property>

Twig
----

.. code-block:: twig

    <span class="icon-{{ content.single }}">
        Icon {{ content.single }}
    </span>
