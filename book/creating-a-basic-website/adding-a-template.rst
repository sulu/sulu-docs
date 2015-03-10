Adding a template
=================

In Sulu a template consists of multiple content types, whereby a content type
describes the way the data is stored in the database and how to enter them in
the administration interface. Pages in the Sulu content section will be based
on one of these templates.

On this page there are the available content types described, how to define
these values in our template configuration file, what you should consider when
creating the HTML templates, and finally how to connect the data from Sulu to
the HTML template.

Available content types
-----------------------

The following list shows the content types delivered by a standard sulu
installation. The first column shows the key, which acts as an unique
identifier. The second one describeds the appearance in the administration
interface, and the last one how the content is returned to the HTML template.

+----------------------+---------------------------------------------+-----------------------------------------+
| Key                  | Appearance                                  | Value                                   |
+======================+=============================================+=========================================+
| text_line            | simple text line                            | text                                    |
+----------------------+---------------------------------------------+-----------------------------------------+
| text_area            | text area                                   | text                                    |
+----------------------+---------------------------------------------+-----------------------------------------+
| text_editor          | text editor with formatting capabalities    | formatted text                          |
+----------------------+---------------------------------------------+-----------------------------------------+
| color                | color picker                                | text                                    |
+----------------------+---------------------------------------------+-----------------------------------------+
| date                 | date picker                                 | text                                    |
+----------------------+---------------------------------------------+-----------------------------------------+
| time                 | text line with a time validation            | text                                    |
+----------------------+---------------------------------------------+-----------------------------------------+
| url                  | text line with an URL validation            | text                                    |
+----------------------+---------------------------------------------+-----------------------------------------+
| email                | text line with an email validation          | text                                    |
+----------------------+---------------------------------------------+-----------------------------------------+
| password             | password field                              | text                                    |
+----------------------+---------------------------------------------+-----------------------------------------+
| phone                | text line for a phone number                | text                                    |
+----------------------+---------------------------------------------+-----------------------------------------+
| internal_links       | widget for adding links to other pages      | resolved pages as defined in parameters |
+----------------------+---------------------------------------------+-----------------------------------------+
| single_internal_link | widget for selecting a single internal link | resolved page as defined in parameters  |
+----------------------+---------------------------------------------+-----------------------------------------+
| smart_content        | widget for configuring smart contents, a    | resolved pages as defined in parameters |
|                      | content type for aggregating multiple pages |                                         |
+----------------------+---------------------------------------------+-----------------------------------------+
| resource_locator     | widget for entering the URL for the page    | text                                    |
+----------------------+---------------------------------------------+-----------------------------------------+
| tag_list             | autocomplete field for entering and adding  | array of texts                          |
|                      | tags                                        |                                         |
+----------------------+---------------------------------------------+-----------------------------------------+
| media_selection      | widget for adding media (images, documents) | array containing another array with     |
|                      | to the page                                 | urls for every format                   |
+----------------------+---------------------------------------------+-----------------------------------------+

Add a template definition
-------------------------
To add a new template to your Sulu installation, you just create a new xml file
in `app/Resources/pages` named by a unique template key. See the following
file for an example of a template definition:

.. code-block:: xml

    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">
        <key>default</key>

        <view>ClientWebsiteBundle:templates:default</view>
        <controller>SuluWebsiteBundle:Default:index</controller>
        <cacheLifetime>2400</cacheLifetime>

        <meta>
            <title lang="en">Default</title>
        </meta>

        <properties>
            <property name="title" type="text_line" mandatory="true">
                <meta>
                    <title lang="en">Title</title>
                </meta>

                <tag name="sulu.rlp.part"/>
            </property>

            <property name="url" type="resource_locator" mandatory="true">
                <meta>
                    <title lang="en">Resourcelocator</title>
                </meta>

                <tag name="sulu.rlp"/>
            </property>

            <property name="images" type="media_selection">
                <meta>
                    <title lang="en">Images</title>
                </meta>
            </property>

            <property name="article" type="text_editor">
                <meta>
                    <title lang="de">Artikel</title>
                    <title lang="en">Article</title>
                </meta>

                <params>
                    <param name="godMode" value="true"/>
                    <param name="links" value="true"/>
                    <param name="tables" value="true"/>
                </params>
            </property>
        </properties>
    </template>

The root element of this xml file is `template`, which first child element is a
key, which has to match the filename without the file extension (e.g. the file
`default.xml` has the key `default`).

The next xml tags contains some information about rendering the template. This
includes the `view`, which is the reference to the twig template, and the
`controller`-tag references the controller, which is used to render the given
template. For standard templates you don't have to define your own controllers,
because you can use the `index`-action of the `DefaultController` in the
`SuluwebsiteBundle`. Both the template and controller have to be referenced
as described in the `Template Naming and Locations`_ (with the addition of the
`LiipThemeBundle`_) and `Controller Naming Pattern`_ in the Symfony
documentation.

The `meta`-tag consists of another `title`-tag for each available language,
which will be displayed in the template selection of the Sulu administration
interface. 

The next tag is for all the `properties` in this template. A property is the
instance of one of the previous listed content types. The property's type
attribute is the key from the list above, and the name identifies this
particular property. The first child element is another `meta`-tag containing
the title for each language, which will be displayed in the content management
form in the Sulu administration. Depending on the content type you can/must add
some more parameters, as for the `article`-property in the example above. The
example is enabling the godMode, the icon for adding links and the icon for
adding tables.

.. note::

   Every template has to define a property named `title`, because it is used
   internally for generating URLs and storing.

Build the HTML template
-----------------------

We recommend to build the HTML templates in a first draft with plain HTML and
some dummy texts. That means absolutely no placeholders for template engines.
This ensures that your HTML is working across all browsers (at least if you
test it correctly), and it is easier to test, since there are guaranteed no
errors caused by the some wrong template variables.

Please make also sure that your HTML is valid, and use HTML tags in a semantic
way, so that your website will achieve the best results in terms of search
engine optimization.

Create the data connection
--------------------------

.. _`Controller Naming Pattern`: http://symfony.com/doc/current/book/routing.html#controller-string-syntax
.. _`Template Naming and Locations`: http://symfony.com/doc/current/book/templating.html#template-naming-locations
.. _`LiipThemeBundle`: https://github.com/liip/LiipThemeBundle#theme-cascading-order

