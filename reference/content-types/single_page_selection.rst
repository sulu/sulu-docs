Single page selection
=====================

Description
-----------

Shows a field, on which exactly one link to another page can be assigned.

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

Example
-------

.. code-block:: xml

    <property name="link" type="single_page_selection">
        <meta>
            <title lang="en">Link</title>
        </meta>
    </property>

Twig
----

Currently this content type only returns the UUID of the target page. In
order to construct a link to the page use:

.. code-block:: html

    {% set target = sulu_content_load(content.link) %}

Then ``target.content`` will give you access to the URL and other properties
of the target page.

.. note::

    The ``sulu_content_load`` twig extension loads the whole page with it content and so
    can have a negative impact on the performance of the page and if possible should be
    avoided.

.. _jexl: https://github.com/TomFrost/jexl
