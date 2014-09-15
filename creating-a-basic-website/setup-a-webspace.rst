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

