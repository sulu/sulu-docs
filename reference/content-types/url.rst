URL
===

Description
-----------

Shows a text line, the inserted content will be validated against an URL regex
and saved as a simple string.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - defaults
      - collection
      - Default values for input (scheme and specificPart).
    * - schemes
      - collection
      - List of available schemes in dropdown and validation.

Example
-------

.. code-block:: xml

    <property name="url" type="url">
        <meta>
            <title lang="en">URL</title>
        </meta>

        <params>
            <param name="defaults" type="collection">
                <param name="scheme" value="http://"/>
                <param name="specific_part" value="www.google.at"/>
            </param>
            <param name="schemes" type="collection">
                <param name="http://"/>
                <param name="https://"/>
            </param>
        </params>
    </property>
