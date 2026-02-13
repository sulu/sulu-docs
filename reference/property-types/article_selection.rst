Article selection
=================

Description
-----------

Shows a list with the possibility to add links to other articles managed in Sulu.
Additionally it populates all the fields defined in the template configuration
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
      - Defines with which key which property of the linked article should be
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
      - The minimum number of selected articles
    * - max
      - string
      - The maximum number of selected articles

Example
-------

.. code-block:: xml

    <property name="articles" type="article_selection">
        <meta>
            <title lang="en">Articles</title>
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

    <property name="articles" type="article_selection">
        <meta>
            <title lang="en">Articles</title>
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

.. code-block:: twig

    {% for article in content.articles %}
        <div>
            <h3>
                {{ article.excerptTitle|default(article.title) }}
            </h3>

            <div>
                {{ article.excerptDescription|default(article.article)|raw }}
            </div>

            <div href="{{ sulu_content_path(article.url) }}">Read more</div>
        </div>
    {% endfor %}
