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

The content type only returns the UUID of the target page at the moment. If you want to
render a link to the page, you can use the <``sulu-link`` tag>:doc:`../../bundles/markup/link`:

.. code-block:: html

    <sulu-link href="{{ content.link }}">Link Text</sulu-link>

If you need to load additional data of the target page, you can use the
<``sulu_content_load`` twig extension>:doc:`../twig-extensions/functions/sulu_content_load`:

.. code-block:: twig

    {% set target = sulu_content_load(content.link, {'title': 'title', 'excerpt.title': 'excerptTitle'}) %}

.. _jexl: https://github.com/TomFrost/jexl
