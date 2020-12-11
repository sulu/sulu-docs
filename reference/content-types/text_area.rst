Text area
=========

Description
-----------

Shows a simple text area, the inserted content will be saved as simple string.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - max_characters
      - string
      - Soft limit for maximum number of characters. Will show a character counter.

Example
-------

.. code-block:: xml

    <property name="description" type="text_area">
        <meta>
            <title lang="en">Description</title>
        </meta>
    </property>

Twig
----

.. code-block:: twig

    {{ content.description }}
