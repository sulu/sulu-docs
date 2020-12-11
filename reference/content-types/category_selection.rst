Category selection
==================

Description
-----------

Shows a list of all available categories. The user can select with a checkbox
which ones to assign to the page. Categories can be managed in the settings
section of Sulu. The selection will be saved as an array.

.. note::

    This content type is rarely needed because the ``Excerpt and Taxonomies``
    allows to assign categories to pages.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - item_disabled_condition
      - string
      - Allows to set a `jexl`_ expression that evaluates if an item should be displayed as disabled.
        Disabled items cannot be selected.
    * - request_parameters
      - collection
      - Collection of parameters that are appended to the requests sent by the selection.
    * - resource_store_properties_to_request
      - collection
      - Collection of property names.
        The value of the respective properties are appended to the requests sent by the selection.
    * - min
      - string
      - The minimum number of selected categories
    * - max
      - string
      - The maximum number of selected categories

Example
-------

.. code-block:: xml

    <property name="categories" type="category_selection">
        <meta>
            <title lang="en">Category Selection</title>
        </meta>
    </property>

Twig
----

.. code-block:: twig

    {% for category in content.categories %}
        <h3>{{ category.name }}</h3>
    {% endif %}

If you want to list all categories in your template you can use the :doc:`../twig-extensions/functions/sulu_categories`
twig extension for it.

.. _jexl: https://github.com/TomFrost/jexl
