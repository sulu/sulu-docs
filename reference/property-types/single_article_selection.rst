Single Article selection
========================

Description
-----------

Shows a field, on which exactly one link to an article can be assigned.
Additionally it populates all the fields defined in the template configuration
to the HTML template. The selected article is exposed to the HTML template as a single object containing the configured properties.

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
    * - template_keys
      - string
      - Comma-separated list of template keys. When set, only articles using one of those
        templates are shown in the selection overlay.
    * - groups
      - string
      - Comma-separated list of article group identifiers. When set, only articles belonging
        to one of those groups are shown in the selection overlay.
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

    <property name="singleArticle" type="single_article_selection">
        <meta>
            <title lang="en">Single Article</title>
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

    <property name="singleArticle" type="single_article_selection">
        <meta>
            <title lang="en">Single Article</title>
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

.. code-block:: twig

    <div>
        <h3>
            {{ content.singleArticle.excerptTitle|default(content.singleArticle.title) }}
        </h3>

        <div>
            {{ content.singleArticle.excerptDescription|default(content.singleArticle.article)|raw }}
        </div>

        <a href="{{ sulu_content_path(content.singleArticle.url) }}">Read more</a>
    </div>

.. _jexl: https://github.com/TomFrost/jexl
