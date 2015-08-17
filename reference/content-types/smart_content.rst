Smart content
=============

Description
-----------

Shows a list of pages, which depend on a configurable filter. You can define
which site's children should be included, what tags the filtered pages
must have, how they are sorted, and how many results you want to get.
Additionally you can define some presentation types, so that the content 
manager can decide if the pages should be displayed e.g. in one column or two
columns. The filter is saved as a JSON string in the database.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - provider
      - string
      - DataProvider alias for content of SmartContent - Default 'content'.
    * - max_per_page
      - integer
      - Limits the results per page. Omit this parameter to disable pagination.
    * - page_parameter
      - string
      - Defines the page number key to be used in the query string.
    * - properties
      - collection
      - Defines the property names which will be exposed in the HTML template.
    * - present_as
      - collection
      - A collection of strings, which can be configured for different
        presentation modes. If more than one element is given, the user can
        choose between the elements in this collection. The selected value is
        also passed to the HTML template.

.. note::

    "properties" can include structure properties or extension data:

    * title - is a property of the structure
    * excerpt.title - is a property of the excerpt structure extension with the name title

Return Value
------------

.. list-table::
    :header-rows: 1

    * -
      - Name
      - Type
      - Description
    * - content
      - uuid
      - string
      - Uuid of page
    * -
      - title
      - string
      - Title of page
    * -
      - url
      - string
      - URL of page
    * -
      - path
      - int
      - path of page
    * -
      - urls
      - string[]
      - Array of localized URLs
    * -
      - nodeType
      - string
      - NodeType of page - content, internal or external
    * -
      - changed
      - DateTime
      - Date and time of last change
    * -
      - changer
      - int
      - Id of user which changed the page
    * -
      - created
      - DateTime
      - Date and time of creation
    * -
      - creator
      - int
      - Id of user which has created the page
    * -
      - template
      - string
      - Name of page-template
    * - view
      - dataSource
      - string
      - Uuid of data-source
    * -
      - includeSubFolders
      - bool
      - Is TRUE if subfolders will be crawled
    * -
      - category
      - string[]
      - Selected categories
    * -
      - tags
      - string[]
      - Selected tags.
    * -
      - sortBy
      - string
      - Selected sort column
    * -
      - sortMethod
      - string
      - Selected sort method - ASC or DESC
    * -
      - presentAs
      - string
      - selected present as value
    * -
      - limitResult
      - string
      - Selected limit for result
    * -
      - page
      - int
      - Current page number
    * -
      - hasNextPage
      - bool
      - Is TRUE if another page exists

Example
-------

Page template
~~~~~~~~~~~~~

.. code-block:: xml

    <property name="smart_content" type="smart_content">
        <meta>
            <title lang="en">Smart Content</title>
        </meta>

        <params>
            <param name="provider" value="content"/>
            <param name="max_per_page" value="5"/>
            <param name="page_parameter" value="p"/>
            <param name="properties" type="collection">
                <param name="article" value="article"/>
                <param name="excerpt.title" value="excerptTitle"/>
                <param name="excerpt.tags" value="excerptTags"/>
                <param name="excerpt.images" value="excerptImages"/>
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

    {% for page in content.pages %}
        <div class="col-lg-{{ view.pages.presentAs == 'two' ? '6' : '12' }}">
            <h2>
                <a href="{{ content_path(page.url) }}">{{ page.title }}</a>
            </h2>
            <p>
                <i>{{ page.excerptTitle }}</i> | <i>{{ page.excerptTags|join(', ') }}</i>
            </p>
            {% if page.excerptImages|length > 0 %}
                <img src="{{ page.excerptImages[0].thumbnails['50x50'] }}" alt="{{ page.excerptImages[0].title }}"/>
            {% endif %}
            {% autoescape false %}
                {{ page.article }}
            {% endautoescape %}
        </div>
    {% endfor %}
