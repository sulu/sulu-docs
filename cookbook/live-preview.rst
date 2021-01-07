How to include live-preview in my template?
===========================================

Sulu provides a powerful live-preview system which can be used by every website
theme. But as a prerequisite the template has to be adapted a little bit.

Quick Example
-------------

The preview-system relies on the schema definition from
`RDFA <https://en.wikipedia.org/wiki/RDFa>`_. This standard allows you to add
information about the content in the html structure without maintaining another
file.

The following example is a very basic template implementation which is able to
be updated by sulu.

**template.html.twig**

.. code-block:: twig

    {% extends "::master.html.twig" %}

    {% block content %}
        <div id="content" vocab="http://schema.org/" typeof="Content">
            <h1 property="title">{{ content.title }}</h1>
        </div>
    {% endblock %}

**master.html.twig**

.. code-block:: twig

    <!DOCTYPE html>
    <html>
        <head>
            <title>{{ content.title }}</title>
        </head>
        <body>
            {% block content %}{% endblock %}
        </body>
    </html>

**page.xml**

.. code-block:: xml

    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">

        <key>page</key>

        <view>ClientWebsiteBundle:templates:default</view>
        <controller>SuluWebsiteBundle:Default:index</controller>
        <cacheLifetime>2400</cacheLifetime>

        <properties>
            <section name="highlight">
                <properties>
                    <property name="title" type="text_line" mandatory="true">
                        <params>
                            <param name="headline" value="true"/>
                        </params>

                        <tag name="sulu.rlp.part"/>
                    </property>

                    <property name="url" type="resource_locator" mandatory="true">
                        <tag name="sulu.rlp"/>
                    </property>
                </properties>
            </section>
        </properties>
    </template>

This page consists of a single title and a url. The template is quite basic but
includes the RDFa attribute `property="title"` to tell sulu that the title is
rendered in this container.

When the title of the page will be updated in the content form the admin will
send this change to the server where the twig block `content` will be rendered.
The server response contains only the part which was changed (indicated by
`property="title"`). Back in the javascript application the preview on the right
will update the container which is marked with the particular property.

This process ensures a quite good performance (as good as the rendering on the
server side) and minimizes the load of the requests.

Supported RDFa features
-----------------------

To see a full example of the syntax take a look in the
`example.html.twig <https://github.com/sulu/sulu-standard/blob/master/src/Client/Bundle/WebsiteBundle/Resources/themes/default/templates/example.html.twig>`
file.

Links
*****

For links you can use the href-attribute which will also be updated if the
mentioned property was changed.

.. code-block:: twig

    <a href="{{ sulu_content_path(content.link.url) }}" property="link">
        {{ content.link.title }}
    </a>

Images
******

To update an image you can simply use the src-attribute when the mentioned
property has changed

.. code-block:: twig

    <img src="{{ image.thumbnails['170x170'] }}" alt="{{ image.title }}"/>


Multiple values
***************

For multiple values simply use table, ul, ol or div tags and the content will
be updated if the mentioned property was changed.

.. code-block:: twig

    <ul property="categories">
        {% for category in content.categories %}
            <li>{{ category.name }}</li>
        {% endfor %}
    </ul>

Snippets
********

For the snippet content-type all the selected snippets has to be updated.
Therefore you only have to set the upper property.

.. code-block:: twig

    <div property="snippets">
        {% for snippet in content.snippets %}
            <h2>{{ snippet.title }}</h2>
        {% endfor %}
    </div>

Smart-Content
*************

Smart-Content will be handled like other properties. You only have to define the
property name (in this example `similar_pages`).

.. code-block:: twig

    <ul property="similar_pages">
    {% for link in content.similar_pages %}
        <li>
            <a href="{{ sulu_content_path(link.url) }}">
                {{ link.title|default('No Title') }}
            </a>
        </li>
    {% endfor %}
    </ul>

Single-Internal-Link
********************

For rendering single-internal links you can use the
:doc:`../bundles/markup/index`. This example will place the title of the page
in the content of the anchor tag.

.. code-block:: twig

    <sulu:link property="similar_pages" href="{{ content.singleInternalLink }}" title="My-Title"/>

Blocks
******

Blocks are the only property-type which needs a different syntax beside the
`property` attribute.

.. code-block:: twig

    <div property="blocks" typeof="collection">
        {% for block in content.blocks %}
            <div rel="blocks" typeof="block">
                <div property="title">{{ block.title }}</div>
            </div>
        {% endfor %}
    </div>

You have to define the `property="blocks"` as `typeof="collection"` and each
item of the block as `typeof="block"` and set the relation to the parent
property, in this case "blocks".

With these definitions the system is able to update only the `title` of the
first block item and doesn't have to return the entire container of the
block-property in the response.
