Setup a Webspace
================

In this chapter we will have a look at webspaces. We will create the
configuration of a basic website: a single webspace, published under one
domain in multiple localizations.

As already described in the section before, a webspace also creates a new
content tree. These trees are accessible by the navigation in the Sulu
administration interface. Sulu allows you to create pages and sub pages in
these trees and fill them with content. Have a closer look at
:doc:`templates` for more details on the content management process.

Normally you'll create a webspace for a new website, a landingpage or a portal,
that should run on your Sulu instance.

The following file shows the configuration of such a webspace. These lines
will be explained in the following paragraphs.

.. code-block:: xml

    <?xml version="1.0" encoding="utf-8"?>
    <webspace xmlns="http://schemas.sulu.io/webspace/webspace"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/webspace/webspace http://schemas.sulu.io/webspace/webspace-1.1.xsd">

        <name>Website</name>
        <key>website</key>

        <localizations>
            <localization language="en"/>
            <localization language="de"/>
        </localizations>

        <default-templates>
            <default-template type="page">default</default-template>
            <default-template type="homepage">homepage</default-template>
        </default-templates>

        <templates>
            <template type="search">search/search</template>
            <template type="error">error/error</template>
        </templates>

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
                <name>Website</name>
                <key>website</key>

                <environments>
                    <environment type="prod">
                        <urls>
                            <url>example.org/{localization}</url>
                        </urls>
                    </environment>
                    <environment type="dev">
                        <urls>
                            <url>example.localhost/{localization}</url>
                        </urls>
                    </environment>
                </environments>
            </portal>
        </portals>
    </webspace>

As you probably already have encountered, the root tag for our webspace
definition is ``webspace``. Afterwards you see a name, which is displayed in the
administration interface. But even more important is the key, which is used
internally to generate some files and define some paths. Therefore it is really
important that the webspace key is unique across all webspaces in a single
installation.

.. note::

    If you add a webspace to an existing installation you also have to set the
    correct permissions for existing users, otherwise they won't be able to see
    it.

Localizations
-------------

In the ``localizations``-tag you can list all the available localizations in this
webspace. In the example we are adding English and German, but you can also
define country specific languages if you add a country attribute to the
localization, so for instance the following tag would add Austrian German to
the available localizations:

.. code-block:: xml

    <localization language="de" country="at" />

For a more complete explanation you should have a look at
:doc:`localization`.

Default Templates
-----------------

The ``default-templates``-tag defines which of your :doc:`page templates
<templates>` is preselected when a content manager creates a new page
(``type="page"``) and which template is used for the homepage of the webspace
(``type="homepage"``). The value is the key of the template, i.e. the name of
its XML file without the extension.

Templates
---------

The ``templates``-tag defines the Twig templates Sulu itself renders for a
webspace, by type. Sulu uses the template with the type ``error-<http-code>``
(e.g. ``error-404``) to render an error page and falls back to the template
with the type ``error``, and it uses the template with the type ``search`` to
display the results of the website search.

Navigation
----------

The ``navigation``-tag defines so called navigation contexts, e.g. the main
navigation and a footer navigation. A content manager can assign a page to
one or more contexts, and the developer renders each context with the Twig
functions shipped with Sulu. Read :doc:`navigation` for the details.

.. _webspace-urls:

URLs
----

A webspace can consist of multiple portals, but usually a single one is
enough: it's the portal that defines under which URLs the content of the
webspace is published. The portal has a ``name`` and a ``key`` like the
webspace itself, whereby the key of the portal has to be unique for the entire
installation, not only within this webspace.

The URLs are defined per environment, which have to match the environments
of Symfony: usually ``dev``, ``stage`` and ``prod`` are available. Each
environment can define its own set of URLs, and every URL has to include the
localization somehow, either by using a placeholder as in the example above
(``{localization}`` expands to every localization of the webspace, resulting in
``example.org/en`` and ``example.org/de``) or by fixing the URL to a specific
localization:

.. code-block:: xml

    <url language="de" country="at">www.example.org</url>

.. note::

    Please consider that you have to omit the port in the configuration. The
    system will work with any port, so you don't have to name it in the
    configuration.

.. tip::

    If you want to match all hosts, e.g. during development, you can use the
    ``{host}`` placeholder: ``<url>{host}/{localization}</url>``.

The available placeholders and the way to spread the localizations of a
webspace over several portals are described in detail in the
:doc:`webspace configuration reference <../bundles/page/webspace-configuration>`.

Further Configuration
---------------------

The configuration above is all that is needed for most websites. The webspace
schema offers some more, optional, tags, which are described in the
:doc:`webspace configuration reference <../bundles/page/webspace-configuration>`:

* ``security``: restrict the pages of the webspace to the users of a separate
  security system, with optional permission checks on the website;
* ``theme``: use a different look and feel per webspace with the
  SuluThemeBundle;
* ``excluded-templates``: hide some page templates in the template dropdown of
  this webspace;
* ``segments``: split the website into segments (e.g. "Winter" and "Summer")
  the visitor can switch between;
* ``resource-locator``: choose how the URLs of the pages are generated and
  updated;
* ``portals``: publish the content of the webspace under several portals, with
  different localizations and URLs each.
