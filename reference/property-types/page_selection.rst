Page Selection
==============

Description
-----------

Shows a list with the possibility to add links to other pages managed in Sulu.
Additionally it populates the title, the url and the fields listed in the ``properties`` parameter
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
    * - item_disabled_condition
      - string
      - Allows to set a `jexl`_ expression that evaluates if an item should be displayed as disabled.
        Disabled items cannot be selected.
    * - allow_deselect_for_disabled_items
      - bool
      - Defines if the user should be able to deselect an item that is disabled. Default value is true.
    * - sortable
      - bool
      - Defines if the user should be able to sort the selected items. Default value is true.
    * - request_parameters
      - collection
      - Collection of parameters that are appended to the requests sent by the selection.
    * - resource_store_properties_to_request
      - collection
      - Collection of property names.
        The value of the respective properties are appended to the requests sent by the selection.
    * - min
      - string
      - The minimum number of selected pages
    * - max
      - string
      - The maximum number of selected pages

Example
-------

.. code-block:: xml

    <property name="pages" type="page_selection">
        <meta>
            <title lang="en">Pages</title>
        </meta>

        <params>
            <param name="properties" type="collection">
                <param name="title" value="title"/>
                <param name="article" value="article"/>
                <param name="excerptTitle" value="excerpt.title"/>
                <param name="excerptDescription" value="excerpt.description"/>
            </param>
        </params>
    </property>

Complex Example
---------------

.. code-block:: xml

    <property name="pages" type="page_selection">
        <meta>
            <title lang="en">Pages</title>
        </meta>

        <params>
            <param name="properties" type="collection">
                <param name="title" value="title"/>
                <param name="article" value="article"/>
                <param name="excerptTitle" value="excerpt.title"/>
                <param name="excerptDescription" value="excerpt.description"/>
                <param name="uuid" value="object.resource.id"/>
            </param>
            <param name="item_disabled_condition" value="title == 'No'"/>
        </params>
    </property>

.. _jexl: https://github.com/TomFrost/jexl

Twig
----

``content.pages`` is a list with one entry per selected page. Each entry contains ``title``, ``url``, ``authored``,
``lastModified`` and the keys defined in the ``properties`` parameter. Other fields of the page template are not
available. A page that cannot be loaded, for example because the visitor may not view it on a website with security
enabled, is removed from the list.

.. code-block:: twig

    {% for page in content.pages %}
        <div>
            <h3>
                {{ page.excerptTitle|default(page.title) }}
            </h3>

            <div>
                {{ page.excerptDescription|default(page.article)|raw }}
            </div>

            <a href="{{ sulu_content_path(page.url) }}">Read more</a>
        </div>
    {% endfor %}

When pages are selected, ``view.pages`` is a flat list in the same order as ``content.pages``. Each entry contains
``uuid``, ``template``, ``webspaceKey`` and ``parent`` (the uuid of the parent page or ``null``) and the view data of
the fields it contains. Without a selection, ``view.pages`` is no list: it holds an empty ``ids`` and the parameters of
the property.

.. code-block:: twig

    {% for page in content.pages %}
        {{ page.title }} ({{ view.pages[loop.index0].template }})
    {% endfor %}
