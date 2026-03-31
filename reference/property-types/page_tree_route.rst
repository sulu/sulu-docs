Route
=====

Description
-----------

The ``page_tree_route`` property type allows to generate urls for **articles** and **custom entities**.

.. note::

    The ``page_tree_route`` property type should not be used on page templates. For pages, use the :doc:`route`
    property type instead.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - mode
      - string
      - Defines the mode of the input field, can either be "tree_full_edit" or "tree_leaf_edit". Default value is "tree_full_edit".
    * - route_schema
      - string
      - Route schema that is used for generating the url.
        For articles or custom entities you can use expression language while ``object`` contains an array object of the ``sulu.rlp.part`` fields.


Example
-------

.. code-block:: xml

    <property name="title" type="text_line" mandatory="true">
        <tag name="sulu.rlp.part"/>
    </property>

    <property name="url" type="page_tree_route" mandatory="true">
        <meta>
            <title lang="en">Resource locator</title>
        </meta>

        <params>
            <param name="mode" value="tree_full_edit"/>
            <param name="route_schema" value="/events/{implode('-', object)}"/>
        </params>
    </property>

Twig
----

You need to use the :doc:`../twig-extensions/functions/sulu_content_path` twig extension
to render the full url.

.. code-block:: twig

    {{ sulu_content_path(content.url) }}
