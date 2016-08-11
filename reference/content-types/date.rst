Date
====

Description
-----------

Shows a text line with an attached date picker. The inserted content will be
saved as a normalized string.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - display_options
      - collection
      - Datepicker options from http://bootstrap-datepicker.readthedocs.org/en/latest/options.html

Example
-------

.. code-block:: xml

    <property name="date" type="date">
        <meta>
            <title lang="en">Date</title>
        </meta>

        <params>
            <param name="display_options" type="collection">
                <param name="format" value="yyyy-mm-dd">
                <param name="startDate" value="2000-01-01">
            </param>
        </params>
    </property>
