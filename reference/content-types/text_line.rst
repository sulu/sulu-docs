Text line
=========

Description
-----------

Shows a simple text line, the inserted content will be saved as simple string.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - headline
      - boolean
      - If true the height and font size of the text line get increased.
    * - max_characters
      - string
      - Soft limit for maximum number of characters. Will show a character counter.
    * - max_segments
      - string
      - Soft limit for maximum number of segments. Will show a segment counter.
    * - segment_delimiter
      - string
      - The delimiter used to split the value into segments (required to use `max_segments`)

Example
-------

.. code-block:: xml

    <property name="title" type="text_line">
        <meta>
            <title lang="en">Title</title>
        </meta>
        <params>
            <param name="headline" value="true"/>
        </params>
    </property>

Twig
----

.. code-block:: twig

    {{ content.title }}
