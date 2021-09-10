Single Collection selection
===========================

Description
-----------

Let you assign one collection from the media section.

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
    * - allow_deselect_for_disabled_items
      - bool
      - Defines if the user should be able to deselect an item that is disabled. Default value is true.
    * - request_parameters
      - collection
      - Collection of parameters that are appended to the requests sent by the selection.
    * - resource_store_properties_to_request
      - collection
      - Collection of property names.
        The value of the respective properties are appended to the requests sent by the selection.

Return value
------------

See the Collection_ class for available variables and functions.

Example
-------

.. code-block:: xml

    <property name="collection" type="single_collection_selection">
        <meta>
            <title lang="en">Collection</title>
        </meta>
    </property>

Twig
----

.. code-block:: twig

    {{ content.collection.fullName }}

.. _Collection: https://github.com/sulu/sulu/blob/2.x/src/Sulu/Bundle/MediaBundle/Api/Collection.php
.. _jexl: https://github.com/TomFrost/jexl
