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
        Defaults are: ``https://``, ``http://``, ``ftp://``, ``ftps``, ``mailto:``, ``tel:``.

Example
-------

.. code-block:: xml

    <property name="url" type="url">
        <meta>
            <title lang="en">URL</title>
        </meta>
    </property>

Extended Example
----------------

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

Twig
----

The content type return the full url which can directly be rendered:

.. code-block:: twig

    <a href="{{ content.url }}">
        {{ content.url }}
    </a>
