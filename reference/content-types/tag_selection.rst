Tag Selection
=============

Description
-----------

Shows a simple text line with an autocomplete feature for the available Tags in
the system. Tags can be managed in the settings section of Sulu. The assigned
tags will be saved as an array.

.. note::

    Tags which do not already exist will be created.

.. note::

    This content type is rarely needed because the ``Excerpt and Taxonomies``
    allows to assign tags to pages.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - min
      - string
      - The minimum number of selected tags
    * - max
      - string
      - The maximum number of selected tags

Example
-------

.. code-block:: xml

    <property name="tags" type="tag_selection">
        <meta>
            <title lang="en">Tags</title>
        </meta>
    </property>

Twig
----

.. code-block:: twig

    {% for tag in content.tags %}
        {{ tag }}
    {% endfor %}
