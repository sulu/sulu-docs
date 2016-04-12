Setup a website!
================

On this page you'll learn how to configure your website. You'll define webspaces
and languages, create templates, set the correct permissions and create a user. 


Webspaces
---------

The content management part of Sulu is built upon Webspaces. Each of these
webspaces configure a content tree, which can be managed with different
localizations. For the start you can just copy the delivered file:

.. code-block:: bash

    cp app/Resources/webspaces/sulu.io.xml.dist app/Resources/webspaces/sulu.io.xml

Basically you can name the file however you want, as long as it is ending with
`.xml`. After copying you should adjust the file according to you installation.
Basically you have to change the `key`-tag to a unique value across all
webspaces of this installation, the name and the URLs. These values are written
in squared brackets in the following example:

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
            <default-templates>
                <default-template type="page">example</default-template>
                <default-template type="homepage">default</default-template>
            </default-templates>
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
                    <localization language="de" x-default="true"/>
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

.. note::

    You have to insert the name of your webspace at `[name]`, the key at `[key]`,
    and the URL of your installation at `[url]`. If you want to run Sulu in
    different environments you also have to change the URLs in the other
    environment tags. Set the correct environment in your Webserver
    configuration. For this have a look at :doc:`vhost` documentation.

Sulu needs these URLs in order to match the given requests to a certain portal
and webspace. Otherwise it would not be possible to know the content of which
webspace should be loaded.

In the portal localization configuration you can define a `default` and a
`x-default` localization. The `default` will be used to determine the locale if
no locale was specified in a requested url. The `x-default` will be used to
generate the hreflang tags for seo optimization. This locale will be used as a
kind of fallback for search-engines.


Templates
---------

All created pages are based on templates, which need to be configured.

So you need some templates to add pages to the system. Therefore, you have to add
some XML-files to the specified folder. These files describe the structure of
the pages, i.e. what kind of content the pages can consist of. For the start
you can just copy some of the delivered files. If you want to learn more
about the templates browsing through the copied file might give you a good 
idea on how they look and what they might do for you.

.. code-block:: bash
    
    cp app/Resources/pages/default.xml.dist app/Resources/pages/default.xml
    cp app/Resources/pages/overview.xml.dist app/Resources/pages/overview.xml
    cp app/Resources/snippets/default.xml.dist app/Resources/snippets/default.xml

With this configuration you will be able to create default pages, which just
contain the most basic content types (a title, an URL, links to other pages,
images, and a text editor), and overview pages, which can aggregate multiple
pages. We also copied a default snippet. Feel free to create your own custom
templates.


Complete the installation
-------------------------

After the installation you have to clear the caches, add some empty folders and
set the appropriate permissions to the cache folders:

Use the following commands for Linux:

.. code-block:: bash

    rm -rf app/cache/*
    rm -rf app/logs/*
    mkdir app/data
    HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
    sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs uploads uploads/* web/uploads web/uploads/* app/data
    sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs uploads uploads/* web/uploads web/uploads/* app/data

Or these commands for Mac OSX:

.. code-block:: bash
    
    rm -rf app/cache/*
    rm -rf app/logs/*
    mkdir app/data
    HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
    sudo chmod +a "$HTTPDUSER allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs uploads uploads/* web/uploads web/uploads/* app/data
    sudo chmod +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs uploads uploads/* web/uploads web/uploads/* app/data

Or these commands for Windows (with IIS web server):

.. code-block:: powershell

    rd app\cache\* -Recurse -Force
    rd app\logs\* -Recurse -Force
    md app\data
    $rule = New-Object System.Security.AccessControl.FileSystemAccessRule -ArgumentList @("IUSR","FullControl","ObjectInherit, ContainerInherit","None","Allow")
    $folders = "app\cache", "app\logs", "app\data", "uploads", "uploads\*", "web\uploads", "web\uploads\*"
    foreach ($f in $folders) { $acl = Get-Acl $f; $acl.SetAccessRule($rule); Set-Acl $f $acl; }

Thanks to the `MassiveBuildBundle`_ we can complete the installation with
another single command, which executes some build targets. These targets cover
the initialization of the database and PHPCR (based on the previously created
configuration files) and loads the fixtures:

.. code-block:: bash
    
    app/console sulu:build prod

If you want to also create a user with the credentials admin/admin you can also
execute the following command:

.. code-block:: bash
    
    app/console sulu:build dev

.. note::

    If you omit the build target as the last parameter you will see a list of 
    all available build targets.

.. warning::
    The name of the build targets should not be confused with the Symfony
    environments, although they are most likely to be executed in the ones
    named after them.


Create a new user
-----------------

In order to login into Sulu you need to create a user. Before you can do that
you have to create the administrator role. You can easily add this role with
the following command:

.. code-block:: bash

    $ app/console sulu:security:role:create

Name the role and choose `Sulu` as the system. Afterwards you just have to
enter the following command on the command line, which will guide you through
the creation in an interactive manner:

.. code-block:: bash 

    $ app/console sulu:security:user:create

Just follow the instructions. Afterwards you'll be able to login into the Sulu
Backend, which is accessible by on one of your configured URLs on the site 
`/admin`.
 
.. _`MassiveBuildBundle`: https://github.com/massiveart/MassiveBuildBundle

So your basic setup is almost ready. Next we'll take a quick tour through the 
admin interface.
