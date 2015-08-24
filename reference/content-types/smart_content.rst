Smart content
=============

Description
-----------

Shows a list of items, which depend on a configurable filter. Depending on
the DataProvider you can define where the items come from (Datasource),
what tags the filtered items must have, how they are sorted, and how many
results you want to get. Additionally you can define some presentation
types, so that the content manager can decide if the items should be displayed
e.g. in one column or two columns. The filter is saved as a JSON string in the
database.

The DataProviders are backend modules which handle the selected filters and
return the items which fit to this filters. There are some predefined ones but
you can add your own DataProvider easily. How you can do this is described in
:doc:`/cookbook/smart-content-data-provider`

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
      - Defines the page number key to be used in the website query string.
    * - tags_parameter
      - string
      - Defines the tags key to be used in the website query string. This comma separated list of tag names will be
        combined (AND) with the selected tags from the backend.
    * - website_tags_operator
      - string
      - OR or AND to define how the tags will be combined in the query.
    * - properties
      - collection
      - Defines the property names which will be exposed in the HTML template.
    * - present_as
      - collection
      - A collection of strings, which can be configured for different
        presentation modes. If more than one element is given, the user can
        choose between the elements in this collection. The selected value is
        also passed to the HTML template.

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
    * - category
      - string[]
      - Selected categories
    * - tags
      - string[]
      - Selected tags.
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

The "content" values depends on the DataProvider and will be described in the next section.

DataProvider
------------

These providers are predefined for Sulu-Entities.

Content Pages
~~~~~~~~~~~~~

Alias: "content"

This provider filters content pages. You can choose a parent page as data
source, whose child pages will be filtered by the DataProvider.

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

**Return Value**

.. list-table::
    :header-rows: 1

    * - Name
      - Type
      - Description
    * - resource
      - Document
      - The full page document from the database.
    * - uuid
      - string
      - Uuid of page
    * - title
      - string
      - Title of page
    * - url
      - string
      - URL of page
    * - path
      - int
      - path of page
    * - fullQualifiedTitle
      - string
      - same as path with trailing '/'
    * - urls
      - string[]
      - Array of localized URLs
    * - nodeType
      - string
      - NodeType of page - content, internal or external
    * - changed
      - DateTime
      - Date and time of last change
    * - changer
      - int
      - Id of user which changed the page
    * - created
      - DateTime
      - Date and time of creation
    * - creator
      - int
      - Id of user which has created the page
    * - template
      - string
      - Name of page-template

.. note::

    Additional values which are configured in "properties" will be
    available to.

Contact - People
~~~~~~~~~~~~~~~~

Alias: "contact"

This provider filters the contacts.

**Return Value**

.. list-table::
    :header-rows: 1

    * - resource
      - Contact
      - The full entity from the database.
    * - formOfAddress
      - string
      - Property of the contact.
    * - title
      - string
      - Property of the contact.
    * - salutation
      - string
      - Property of the contact.
    * - fullName
      - string
      - Property of the contact.
    * - firstName
      - string
      - Property of the contact.
    * - lastName
      - string
      - Property of the contact.
    * - middleName
      - string
      - Property of the contact.
    * - birthday
      - string
      - Property of the contact.
    * - created
      - string
      - Property of the contact.
    * - creator
      - string
      - Property of the contact.
    * - changed
      - string
      - Property of the contact.
    * - changer
      - string
      - Property of the contact.
    * - medias
      - Media[]
      - Medias of the contact. Can be resolved via Twig-Function :doc:`/reference/twig-extensions/functions/sulu_resolve_medias`
    * - emails
      - string[]
      - Property of the contact.
    * - phones
      - string[]
      - Property of the contact.
    * - faxes
      - string[]
      - Property of the contact.
    * - urls
      - string[]
      - Property of the contact.
    * - tags
      - string[]
      - Property of the contact.
    * - categories
      - string[]
      - Property of the contact.

Account - Organization
~~~~~~~~~~~~~~~~~~~~~~

Alias: "account"

This provider filters the accounts.

**Return Value**

.. list-table::
    :header-rows: 1

    * - resource
      - Account
      - The full entity from the database.
    * - number
      - string
      - Property of the account.
    * - name
      - string
      - Property of the account.
    * - registerNumber
      - string
      - Property of the account.
    * - placeOfJurisdiction
      - string
      - Property of the account.
    * - uid
      - string
      - Property of the account.
    * - corporation
      - string
      - Property of the account.
    * - created
      - string
      - Property of the account.
    * - creator
      - string
      - Property of the account.
    * - changed
      - string
      - Property of the account.
    * - changer
      - string
      - Property of the account.
    * - medias
      - Media[]
      - Medias of the account. Can be resolved via Twig-Function :doc:`/reference/twig-extensions/functions/sulu_resolve_medias`
    * - emails
      - string[]
      - Property of the account.
    * - phones
      - string[]
      - Property of the account.
    * - faxes
      - string[]
      - Property of the account.
    * - urls
      - string[]
      - Property of the account.
    * - tags
      - string[]
      - Property of the account.
    * - categories
      - string[]
      - Property of the account.

.. _example:

Example for "content" DataProvider
----------------------------------

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
