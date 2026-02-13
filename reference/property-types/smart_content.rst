Smart content
=============

Description
-----------

Shows a list of items, which depend on a configurable filter. Depending on
the SmartContentProvider you can define where the items come from (Datasource),
what tags the filtered items must have, how they are sorted, and how many
results you want to get. Additionally you can define some presentation
types, so that the content manager can decide if the items should be displayed
e.g. in one column or two columns. The filter is saved as a JSON string in the
database.

SmartContentProviders are backend modules which handle the selected filters and
return the items that match these filters. There are several built-in providers
and you can add your own SmartContentProvider easily. How you can do this is
described in :doc:`/cookbook/smart-content-data-provider`

A very important feature is the ``exclude_duplicates`` parameter which offers
the possibility to filter already used items on a website. If this parameter
is set to true the smart-content uses the :doc:`/bundles/website/reference-store`
to detect already used items and filters them.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - provider
      - string
      - SmartContentProvider alias for content of SmartContent. Default: `pages`
    * - max_per_page
      - integer
      - Limits the results per page. Omit this parameter to disable pagination.
    * - page_parameter
      - string
      - Defines the page number key to be used in the website query string. Default: `p`
    * - tags_parameter
      - string
      - Defines the tags key to be used in the website query string. This comma
        separated list of tag names will be combined (AND) with the selected
        tags from the backend. Default: `tags`
    * - categories_parameter
      - string
      - Defines the categories key to be used in the website query string. This
        comma separated list of category ids will be combined (AND) with the
        selected tags from the backend. Default: `categories`
    * - website_tags_operator
      - string
      - OR or AND to define how the tags will be combined in the query. Default: `OR`
    * - website_categories_operator
      - string
      - OR or AND to define how the categories will be combined in the query. Default: `OR`
    * - properties
      - collection
      - Defines the property names which will be exposed in the HTML template.
    * - present_as
      - collection
      - A collection of strings, which can be configured for different
        presentation modes. If more than one element is given, the user can
        choose between the elements in this collection. The selected value is
        also passed to the HTML template.
    * - category_root
      - string
      - Root category (key) to display category-tree.
    * - exclude_duplicates
      - bool
      - If the provider is able to detect duplicates the content-type filters
        already loaded records. Default: `false`

Return Value
------------

This values are available in the *view* variable in the twig templates.

.. list-table::
    :header-rows: 1

    * - Name
      - Type
      - Description
    * - dataSource
      - string
      - Uuid of data-source
    * - includeSubFolders
      - bool
      - Is TRUE if subfolders will be crawled
    * - categories
      - string[]
      - Selected categories
    * - categoryOperator
      - string
      - Operator which combines selected categories
    * - tags
      - string[]
      - Selected tags
    * - tagOperator
      - string
      - Operator which combines selected tags
    * - types
      - string[]
      - Selected types
    * - websiteCategories
      - string[]
      - Selected categories over GET parameter
    * - websiteCategoryOperator
      - string
      - Operator which combines GET parameter categories
    * - websiteTags
      - string[]
      - Selected tags over GET parameter
    * - websiteTagOperator
      - string
      - Operator which combines GET parameter tags
    * - sortBy
      - string
      - Selected sort column
    * - sortMethod
      - string
      - Selected sort method - ASC or DESC
    * - presentAs
      - string
      - selected present as value
    * - limitResult
      - string
      - Selected limit for result
    * - page
      - int
      - Current page number
    * - hasNextPage
      - bool
      - Is TRUE if another page exists

The "content" values depends on the SmartContentProvider.

.. note::

    You can determine content properties with the twig function ``dump``.

SmartContentProvider
--------------------

Sulu includes several built-in SmartContentProviders for common Sulu resources.

Content Pages
~~~~~~~~~~~~~

Alias: "pages"

This provider filters content pages. You can choose a parent page as data
source, whose child pages will be filtered by the SmartContentProvider.

**Parameters**

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - properties
      - collection
      - Defines the property names which will be exposed in the HTML template.

.. note::

    "properties" can include structure properties or extension data:

    * title - is a property of the structure
    * excerpt.title - is a property of the excerpt structure extension with
      the name title

    For an example see :ref:`example`

Articles
~~~~~~~~~~~~~

Alias: "articles"

This provider filters articles.

**Parameters**

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - properties
      - collection
      - Defines the property names which will be exposed in the HTML template.

.. note::

    "properties" can include structure properties or extension data:

    * title - is a property of the structure
    * excerpt.title - is a property of the excerpt structure extension with
      the name title

    For an example, see the :ref:`example` section of the "pages" provider. Use the same configuration, but change the provider alias to "articles".

Snippet
~~~~~~~

Alias: "snippets"

This provider filters snippets.

**Parameters**

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - type
      - string
      - If defined only snippets from this type will be returned
    * - properties
      - collection
      - Defines the property names which will be exposed in the HTML template.

Contact - People
~~~~~~~~~~~~~~~~

Alias: "contacts"

This provider filters the contacts.

Account - Organization
~~~~~~~~~~~~~~~~~~~~~~

Alias: "accounts"

This provider filters the accounts.

Media
~~~~~

Alias: "media"

This provider filters the media.


**Parameters**

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - mimetype_parameter
      - string
      - name of mime-type GET parameter (default: `mimetype`)
    * - type_parameter
      - string
      - name of media-type GET parameter (default: `type`)


Additionally the provider provides some additional filter for the website. With
the PropertyParameter `mimetype_parameter` and `type_parameter` the name of the
GET parameter can be specified.

For example the MimeType can be filtered by adding `?mimetype=application/pdf`
to the content URL. Same takes effect for `?type=image` with the media type
(which is basically a group of mime-types).

.. _example:

Example for "pages" SmartContentProvider
-----------------------------------------

Page template
~~~~~~~~~~~~~

.. code-block:: xml

    <property name="pages" type="smart_content">
        <meta>
            <title lang="en">Smart Content</title>
        </meta>

        <params>
            <param name="provider" value="pages"/>
            <param name="max_per_page" value="5"/>
            <param name="page_parameter" value="p"/>

            <param name="properties" type="collection">
                <param name="article" value="article"/>
                <param name="excerptTitle" value="excerpt.title" />
                <param name="excerptDescription" value="excerpt.description "/>
                <param name="excerptMore" value="excerpt.more" />
                <param name="excerptTags" value="excerpt.tags" />
                <param name="excerptCategories" value="excerpt.categories" />
                <param name="excerptImage" value="excerpt.image" />
                <param name="excerptIcon" value="excerpt.icon" />
            </param>

            <param name="present_as" type="collection">
                <param name="two">
                    <meta>
                        <title lang="en">Two columns</title>
                    </meta>
                </param>

                <param name="one">
                    <meta>
                        <title lang="en">One column</title>
                    </meta>
                </param>
            </param>
        </params>
    </property>

Twig template
~~~~~~~~~~~~~

.. code-block:: twig

    <ul class="pagination">
        {% set page = view.pages.page %}

        {% if page-1 >= 1 %}
            <li><a href="{{ sulu_content_path(content.url) }}?p={{ page-1 }}">&laquo;</a></li>
        {% endif %}

        {% if view.pages.hasNextPage %}
            <li><a href="{{ sulu_content_path(content.url) }}?p={{ page+1 }}">&raquo;</a></li>
        {% endif %}
    </ul>

    <div property="pages">
        {% for page in content.pages %}
            <div class="col-lg-{{ view.pages.presentAs == 'two' ? '6' : '12' }}">
                <h2>
                    <a href="{{ sulu_content_path(page.url) }}">{{ page.title }}</a>
                </h2>

                <p>
                    <i>{{ page.excerptTitle }}</i> | <i>{{ page.excerptTags|join(', ') }}</i>
                </p>

                {% if page.excerptImages|length > 0 %}
                    <img src="{{ page.excerptImages[0].thumbnails['50x50'] }}" alt="{{ page.excerptImages[0].title }}"/>
                {% endif %}

                {{ page.article|raw }}
            </div>
        {% endfor %}
    </div>

.. note::

    If you have not defined the parameter ``max_per_page`` you can omit the
    pagination.

Built-in SmartContentProviders
-------------------------------

Sulu includes the following built-in SmartContentProviders:

**Pages** (alias: ``pages``)
    Filters content pages from the page tree. Supports tags, categories, types,
    datasource, pagination, sorting, and audience targeting.

**Snippets** (alias: ``snippets``)
    Filters snippets. Supports tags, categories, types, pagination, sorting, and
    audience targeting.

**Articles** (alias: ``articles``, if SuluArticleBundle is installed)
    Filters articles. Supports tags, categories, types (via article types/groups),
    pagination, and sorting.

**Media** (alias: ``media``)
    Filters media items. Supports tags, categories, types (document/video/image/audio),
    datasource (collections), pagination, and sorting.

**Contacts** (alias: ``contacts``)
    Filters contacts/people. Supports tags, categories, pagination, and sorting.

**Accounts** (alias: ``accounts``)
    Filters accounts/organizations. Supports tags, categories, pagination, and sorting.

For creating custom SmartContentProviders, see :doc:`/cookbook/smart-content-data-provider`.
