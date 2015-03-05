Smart content
=============

Description
-----------

Shows a list of pages, which depend on a configurable filter. You can define
which which site's children should be included, what tags the filtered pages
must have, how they are sorted, and how many results you want to get.
Additionally you can define some presentation types, so that the content 
manager can decide if the pages should be displayed e.g. in one column or two
columns. The filter is saved as a JSON string in the database.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - max_per_page
      - integer
      - Limits the results per page. Omit this parameter to disable pagination.
    * - page_parameter
      - string
      - Defines the page number key to be used in the query string
    * - properties
      - collection
      - Defines the property names which will be exposed in the HTML template
    * - present_as
      - collection
      - A collection of strings, which can be configured for different
        presentation modes. If more than one element is given, the user can
        choose between the elements in this collection. The selected value is
        also passed to the HTML template.

Example
-------

.. code-block:: xml

    <property name="smart_content" type="smart_content">
        <meta>
            <title lang="en">Smart Content</title>
        </meta>

        <params>
            <param name="max_per_page" value="5"/>
            <param name="page_parameter" value="p"/>
            <param name="properties" type="collection">
                <param name="title" value="title"/>
                <param name="article" value="article"/>
            </param>
            <param name="present_as" type="collection">
                <param name="two">
                    <meta>
                        <title lang="en">Two columns</title>
                    </meta>
                </param>
                <param name="one">
                    <meta>
                        <title lang="en">One column</title>
                    </meta>
                </param>
            </param>
        </params>
    </property>
