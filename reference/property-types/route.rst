Route
=====

Description
-----------

The ``route`` property type allows to generate urls for **pages**, **articles** and **custom entities**.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - mode
      - string
      - Defines the mode of the input field, can either be "tree_full_edit" or "tree_leaf_edit". Default value is "tree_full_edit". Pages get there config from webspace configuration.
    * - route_schema
      - string
      - Route schema that is used for generating the url.
        For pages, the route schema is defined in the webspace configuration, so you usually do not need to set this parameter.
        For articles and custom entities, you can define the schema using expression language. Within the expression, the variable ``object`` is available and contains an array with the values of all properties tagged with the ``sulu.rlp.part`` tag (see the example below).


Example
-------

.. code-block:: xml

    <property name="title" type="text_line" mandatory="true">
        <tag name="sulu.rlp.part"/>
    </property>

    <property name="url" type="route" mandatory="true">
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
