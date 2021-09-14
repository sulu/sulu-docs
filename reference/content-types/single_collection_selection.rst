Single Collection selection
===========================

Description
-----------

Let you assign one collection from the media section.

.. note::

    This content type passes a Collection_ entity to the twig template. It does not provide the media
    entities inside of the selected collections.
    If you want to access the media entities of a collection, you should use a :doc:`smart_content property <smart_content>`
    with the ``media`` data provider or load the matching media entities in a  :doc:`custom controller <../../cookbook/custom-controller>`
    or in a `custom twig extension`_.

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
.. _custom twig extension: https://symfony.com/doc/current/templating/twig_extension.html
.. _jexl: https://github.com/TomFrost/jexl
