Internal links
==============

Description
-----------

Shows a list with the possibility to add links to other pages managed in Sulu.
Additionally it populates all the fields defined in the template configuration
to the HTML template. The content is stored as an array of references.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - properties
      - collection
      - Defines with which key which property of the linked page should be
        populated to the HTML template.

Example
-------

.. code-block:: xml

    <property name="links" type="internal_links">
        <meta>
            <title lang="en">Links</title>
        </meta>

        <params>
            <param name="properties" type="collection">
                <param name="title" value="title"/>
                <param name="article" value="article"/>
            </param>
        </params>
    </property>
