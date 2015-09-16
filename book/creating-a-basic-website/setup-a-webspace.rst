Setup a Webspace
================

In this chapter we will have a look at webspaces. Therefore we will
create a configuration for a basic website. This will result in a single portal
website with multiple localizations.

As already described in the section before, a webspace also creates a new
content tree. These trees are accessible by the navigation in the Sulu
administration interface. Sulu allows you to create pages and sub pages in
these trees and fill them with content. Have a closer look at
:doc:`adding-a-template` for more details on the content management process.

Normally you'll create a webspace for a new website, a landingpage or a portal, 
that should run on your Sulu instance.

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
            <default-templates>
                <default-template type="page">example</default-template>
                <default-template type="homepage">default</default-template>
            </default-templates>    
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


Localizations
-------------

In the `localizations`-tag you can list all the available localizations in this
webspace. In the example we are simply adding the english language, but you can
also define country specific language if you add a country attribute to the
localization, so for instance the following tag would add austrian german to
the available localizations:

.. code-block:: xml

    <localization language="de" country="at" />

For a more complete explanation you should have a look at
:doc:`adding-localizations`.


Themes
------

The `theme` is described by a key. This key leads to a certain theme,
implemented by a developer in the system. Read more about themes in the section
:doc:`adding-a-theme`.


Navigation
----------

It's also possible to define some so called navigation contexts, which allows
the user to add pages to different kind of navigations. The different contexts
can be defined in the `navigation`-section, and this selection will be
available to choose from in the administration interface. Afterwards the
developer can retrieve the navigation for a given context by using some
Twig-extensions delivered with Sulu.


Portals
-------

A webspace can itself consist of multiple portals. In our simple configuration
file we make use of only one portal. The idea is that the same content can be
shared among different portals and URLs. The portals can then also define for
themeselve in which localization they publish the content, so that you can
spread different localizations over different URLs.

Our sample file defines just one portal, which includes a `name` and a `key`
just as the webspace, wherby the key for the portal hast to be unique for the
entire installation, not only within this webspace.

Then the `strategy` for the `resource-locator` is defined, which influences
the design of the URLs for the content. Currently there is only the
`tree`-option available resulting in exposing the entire content tree in the
URL.


URLs
~~~~

The most important part of the portal configuration are the environments,
because they are including the URLs for the portal. A portal can have multiple
environments, which have to match the environments defined in Symfony. Usually
`dev`, `stage` and `prod` are available. Each environment can define its own
set of URLs. The URLs also have to include the localization somehow. You have
two possibilities to do so:


Fixing an URL to a specific localization
........................................

The above example shows this possibility, where you fix one URL to exactly one
localization. The following fragment shows again how to this:

.. code-block:: xml

    <url language="de" country="at">www.example.org</url>

Since it is possible to define localizations without a country, this attribute
is also optional here. However, the combination of language and country here
must result in an existing localization.


Using placeholders in the URL definition
........................................

Another possibility is to create the URL with a placeholder. Have a look at the
following line for an example:

.. code-block:: xml

    <url>www.example.org/{localization}</url>

Placeholder are expressions in curly braces, which will be expanded to every
possible value. For the above example that means, that an URL for every
localization defined will be generated. So if you have a localization `de-at`
and `en-gb`, the system will create URLs for `www.example.org/de-at` and 
`www.example.org/en-us`.

In the following table all the possible placeholders are listed, and explains
the values of them by using the `de-at`-localization:

+----------------+----------------------------------------+--------------------+
| Placeholder    | Description                            | Example for `de-at`|
+================+========================================+====================+
| {localization} | The name of the entire localization    | `de-at`            |
+----------------+----------------------------------------+--------------------+
| {language}     | The name of the language               | `de`               |
+----------------+----------------------------------------+--------------------+
| {country}      | The name of the country, only makes    | `at`               |
|                | sense in combination with `{language}` |                    |
+----------------+----------------------------------------+--------------------+

Now you got your webspace ready, we will create a template for a page that could
be added to the webspace.
