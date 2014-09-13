Setup
=============

Webspaces
---------
The content management part of Sulu is built upon Webspaces. Each of these
webspaces configure a content tree, which can be managed in different
localizations. For the start you can just copy the delivered file:

.. code-block:: bash

    cp app/Resources/webspaces/sulu.io.xml.dist app/Resources/webspaces/sulu.io.xml

Basically you can name the file however you want, as long as it is ending with
`.xml`. After copying you should adjust the file according to you installation.
Basically you have to change the `key`-tag to a unique value across all
webspaces of this installation, the name and the URLs. These values are shown
in bold in the following example:

.. code-block:: xml

    <?xml version="1.0" encoding="utf-8"?>
    <webspace xmlns="http://schemas.sulu.io/webspace/webspace"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/webspace/webspace http://schemas.sulu.io/webspace/webspace-1.0.xsd">

        <name>[name]</name>
        <key>[key]</key>

        <localizations>
            <localization language="en" shadow="auto">
                <localization language="en" country="us" shadow="auto"/>
            </localization>
            <localization language="de">
                <localization language="de" country="at"/>
            </localization>
        </localizations>

        <theme>
            <key>default</key>
            <excluded>
                <template>overview</template>
            </excluded>
        </theme>

        <navigation>
            <contexts>
                <context key="main">
                    <meta>
                        <title lang="de">Hauptnavigation</title>
                        <title lang="en">Mainnavigation</title>
                    </meta>
                </context>
                <context key="footer">
                    <meta>
                        <title lang="de">Footernavigation</title>
                        <title lang="en">Footernavigation</title>
                    </meta>
                </context>
            </contexts>
        </navigation>

        <portals>
            <portal>
                <name>sulu.io</name>
                <key>sulu_io</key>
                <resource-locator>
                    <strategy>tree</strategy>
                </resource-locator>

                <localizations>
                    <localization language="en" default="true"/>
                    <localization language="de"/>
                </localizations>

                <environments>
                    <environment type="prod">
                        <urls>
                            <url language="en" country="us">sulu.us</url>
                            <url language="de" country="at">www.sulu.io</url>
                            <url>sulu.lo/{localization}</url>
                        </urls>
                    </environment>
                    <environment type="stage">
                        <urls>
                            <url>stage.sulu.lo/{localization}</url>
                            <url>sulu.lo/{localization}</url>
                        </urls>
                    </environment>
                    <environment type="dev">
                        <urls>
                            <url>[url]</url>
                            <url language="en" country="us">localhost</url>
                        </urls>
                    </environment>
                </environments>
            </portal>
        </portals>
    </webspace>

Insert the name of your webspace at `[name]`, the key at `[key]`, and the URL
of your installation at `[url]`. If you want to run Sulu in different
environments you also have to change the URLs in the other environment tags.

Templates
---------
All created pages are based on templates, which have also to be configured.
So you need some templates to add pages to the system. Therfore you have to add
some XML-files to the specified folder. These files describe the structur of
the pages, i.e. what kind of content the pages can consist of. For the start
you can just copy some of the delivered files:

.. code-block:: bash
    
    cp app/Resources/templates/default.xml.dist app/Resources/templates/default.xml
    cp app/Resources/templates/overview.xml.dist app/Resources/templates/overview.xml

With this configuration you will be able to create default pages, just
containg the most basic content types (a title, an URL, links to other pages,
images, and a text editor), and overview pages, which can aggregate multiple
pages.

Complete the installation
-------------------------

After the installation you have to clear the caches, add some empty folders and
set the appropriate permissions to the cache folders:

.. code-block:: bash
    
    rm -rf app/cache/*
    rm -rf app/logs/*
    mkdir app/data
    mkdir uploads/media
    mkdir web/uploads/media
    APACHEUSER=`ps aux | grep -E '[a]pache|[h]ttpd' | grep -v root | head -1 | cut -d\  -f1`
    sudo chmod +a "$APACHEUSER allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs uploads/media web/uploads/media app/data
    sudo chmod +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs uploads/media web/uploads/media app/data

Thanks to the `MassiveBuildBundle`_ we can complete the installation with
another single command, which executes some build targets. These targets cover
the initialization of the database and PHPCR (based on the previously created
configuration files) and loads the fixtures:

.. code-block:: bash
    
    app/console sulu:build

Create a new user
-----------------
In order to login into Sulu you need to create an user. Therefore you just have
to enter the following command on the command line, which will guide you
through the creation in an interactive manner:

.. code-block:: bash 

    app/console sulu:security:user:create

Just follow the instructions. Afterwards you'll be able to login into the Sulu
Backend, which is accessible by on one of your configured URLs on the site `/admin`.

