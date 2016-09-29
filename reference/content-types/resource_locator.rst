Resource locator
================

Description
-----------

Shows a text line with a non-editable prefix, which represents the routes to
this position in the content tree. The part of the current page can be edited
in the available text line. Additionally there is a button with the URL history
of the current page, where parts of the history can also be deleted or
reactivated.

Tags
----

.. list-table::
    :header-rows: 1

    * - Tag
      - Description
    * - sulu.rlp
      - The resource locator with this tag defines the URL to a specific page.
    * - sulu.rlp.part
      - Fields marked with this tag are used to generate the URL for a specific page.
        If more than one field ist marked, the values of these fields will be concatenated into the resource locator.

Parameters
----------

No parameters available

Example
-------

.. code-block:: xml

    <property name="title" type="text_line">
        <tag name="sulu.rlp.part"/>
    </property>
    <property name="subtitle" type="text_line">
        <tag name="sulu.rlp.part"/>
    </property>
    <property name="resource_locator" type="resource_locator">
        <meta>
            <title lang="en">Resource locator</title>
        </meta>

        <tag name="sulu.rlp"/>
    </property>
