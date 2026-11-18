Single Page selection
=====================

Description
-----------

Shows a field, on which exactly one link to another page can be assigned.
Additionally it populates the title, the url and the fields listed in the ``properties`` parameter
to the HTML template. The selected page is exposed to the HTML template as a single object containing the configured properties.

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
    * - request_parameters
      - collection
      - Collection of parameters that are appended to the requests sent by the selection.
    * - resource_store_properties_to_request
      - collection
      - Collection of property names.
        The value of the respective properties are appended to the requests sent by the selection.

Example
-------

.. code-block:: xml

        <property name="page" type="single_page_selection">
            <meta>
                <title lang="en">Single Page</title>
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

        <property name="page" type="single_page_selection">
            <meta>
                <title lang="en">Single Page</title>
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

Twig
----

``content.page`` is an object with ``title``, ``url``, ``authored``, ``lastModified`` and the keys defined in the
``properties`` parameter. Other fields of the page template are not available. It is ``null`` if no page is selected or
the page cannot be loaded, for example because the visitor may not view it on a website with security enabled.

.. code-block:: twig

    {% if content.page %}
        <div>
            <h3>
                {{ content.page.excerptTitle|default(content.page.title) }}
            </h3>

            <div>
                {{ content.page.excerptDescription|default(content.page.article)|raw }}
            </div>

            <a href="{{ sulu_content_path(content.page.url) }}">Read more</a>
        </div>
    {% endif %}

``view.page`` is an object with ``id``, ``uuid``, ``template``, ``webspaceKey`` and ``parent`` (the uuid of the parent
page or ``null``), the view data of the fields it contains and the parameters of the property. If no page is selected,
it only contains ``id`` (``null``) and the parameters.

.. code-block:: twig

    {{ view.page.uuid }}

.. _jexl: https://github.com/TomFrost/jexl