Setup a Webspace
================
In this chapter we will go a bit deeper into webspaces. Therefore we will
create a configuration for a basic website. This will result in a single portal
website with multiple localizations.

As already described in the section before, a webspace also creates a new
content tree. These trees are accessible by the navigation in the Sulu
administration interface. Sulu allows you to create pages and sub pages in
these trees and fill them with content. Have a closer look at
:doc:`adding-a-template` for more details on the content management process.

The configuration file
----------------------
The following file shows the simplest configuration possible. These lines will
be explained in the following paragraphs.

.. code-block:: xml

    <?xml version="1.0" encoding="utf-8"?>
    <webspace xmlns="http://schemas.sulu.io/webspace/webspace"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/webspace/webspace http://schemas.sulu.io/webspace/webspace-1.0.xsd">

        <name>Example</name>
        <key>example</key>

        <localizations>
            <localization language="en"/>
        </localizations>

        <theme>
            <key>default</key>
        </theme>

        <navigation>
            <contexts>
                <context key="main">
                    <meta>
                        <title lang="en">Mainnavigation</title>
                    </meta>
                </context>
            </contexts>
        </navigation>

        <portals>
            <portal>
                <name>example</name>
                <key>example</key>
                <resource-locator>
                    <strategy>tree</strategy>
                </resource-locator>

                <environments>
                    <environment type="prod">
                        <urls>
                            <url language="en">example.org</url>
                        </urls>
                    </environment>
                    <environment type="dev">
                        <urls>
                            <url language="en">example.lo</url>
                        </urls>
                    </environment>
                </environments>
            </portal>
        </portals>
    </webspace>

As you probably already have encountered, the root tag for our webspace
definition is `webspace`. Afterwards you see a name, which is displayed in the
administration interface. But even more important is the key, which is used
internally to generate some files and define some paths. Therfore it is really
important that the webspace key is unique across all webspaces in a single
installation.

In the `localizations`-tag you can list all the available localizations in this
webspace. In the example we are simply adding the english language, for a more
complete explanation you should have a look at :doc:`adding-localizations`.

The `theme` is described by a key. This key leads to a certain theme,
implemented by a developer in the system. Read more about themes in the section
:doc:`adding-a-theme`.

It's also possible to define some so called navigation contexts, which allows
the user to add pages to different kind of navigations. The different contexts
can be defined in the `navigation`-section, and this selection will be
available to choose from in the administration interface. Afterwards the
developer can retrieve the navigation for a given context by using some
Twig-extensions delivered with Sulu.

