SnippetBundle
=============

The SnippetBundle contains the implementation to use snippets in Sulu.

What is a Snippet
-----------------

As the name suggests, a snippet is a small fragment on a page.
However, unlike blocks, for example, which would also fit this description, the idea with snippets is reusability.
As a section of a web page a snippet must be first universally maintained, and thereafter be reused anywhere on the website.
An example on a website would be a social media section.

In this section there would be logos of social services like Facebook and a link to the profile on the service.
This section could of course also be built conventionally in a page template, but you would have to maintain it on each page.

This is where snippets come into play.
A snippet could be configured to cover exactly this use case and you would only have to maintain the profiles once and could reuse them at any point.

Creating a Snippet Template
---------------------------

Creating a snippet Template isn't really different like Page :doc:`../book/templates`.
Create a XML File in your `config/template/snippets/` folder like the following example

.. note::

    The <key> and the name of the XML must be the same!

.. code-block:: xml

    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">

        <key>social_media</key>

        <meta>
            <title lang="en">Social Media</title>
            <title lang="de">Social Media</title>
        </meta>

        <properties>
            <property name="title" type="text_line" mandatory="true">
                <meta>
                    <title lang="en">Title</title>
                    <title lang="de">Titel</title>
                </meta>
                <tag name="sulu.node.name"/>
            </property>

            <property name="facebookImage" type="single_media_selection">
                <meta>
                    <title lang="en">Facebook Icon</title>
                    <title lang="de">Facebook Icon</title>
                </meta>
            </property>

            <property name="facebookLink" type="url">
                <meta>
                    <title lang="en">Facebook Link</title>
                    <title lang="de">Facebook Link</title>
                </meta>
                <params>
                    <param name="schemes" type="collection">
                        <param name="http://"/>
                        <param name="https://"/>
                    </param>
                </params>
            </property>
        </properties>
    </template>

Properties
----------

Properties are the same as Page :doc:`../book/templates`.

Load Snippets from a Subfolder
------------------------------
By the means of configuration in `config/packages/sulu_admin.yaml` according to the following scheme
it is also possible to load snippet templates from custom folders.

.. code-block:: yaml

    sulu_core:
        content:
            structure:
                paths:
                    event_snippets:
                        path: "%kernel.project_dir%/config/template/events/snippets/"
                        type: "snippet"

In this example, a new Events folder has been specified. It is important that the key for the configuration remains unique for each config.
