Setup a Webspace
================

In this chapter we will have a look at webspaces. Therefore we will
create a configuration for a basic website. This will result in a single portal
website with multiple localizations.

As already described in the section before, a webspace also creates a new
content tree. These trees are accessible by the navigation in the Sulu
administration interface. Sulu allows you to create pages and sub pages in
these trees and fill them with content. Have a closer look at
:doc:`templates` for more details on the content management process.

Normally you'll create a webspace for a new website, a landingpage or a portal,
that should run on your Sulu instance.

The following file shows a configuration. These lines will be explained in the
following paragraphs.

.. code-block:: xml

    <?xml version="1.0" encoding="utf-8"?>
    <webspace xmlns="http://schemas.sulu.io/webspace/webspace"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/webspace/webspace http://schemas.sulu.io/webspace/webspace-1.1.xsd">

        <name>Example</name>
        <key>example</key>

        <security permission-check="true">
            <system>example</system>
        </security>

        <localizations>
            <localization language="en"/>
        </localizations>

        <default-templates>
            <default-template type="page">example</default-template>
            <default-template type="homepage">default</default-template>
        </default-templates>

        <templates>
            <template type="search">search/search</template>
            <template type="error">error/error</template>
        </templates>

        <excluded-templates>
            <excluded-template>overview</excluded-template>
        </excluded-templates>

        <navigation>
            <contexts>
                <context key="main">
                    <meta>
                        <title lang="en">Mainnavigation</title>
                    </meta>
                </context>
            </contexts>
        </navigation>

        <segments>
            <segment key="w" default="true">
                <meta>
                    <title lang="en">Winter</title>
                    <title lang="de">Winter</title>
                </meta>
            </segment>
            <segment key="s" default="false">
                <meta>
                    <title lang="en">Summer</title>
                    <title lang="de">Sommer</title>
                </meta>
            </segment>
        </segments>

        <resource-locator>
            <strategy>tree_leaf_edit</strategy>
        </resource-locator>

        <portals>
            <portal>
                <name>example</name>
                <key>example</key>

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

.. note::

    If you want to match all hosts you can use the ``{host}`` placeholder.
    Example: ``<url>{host}/{localization}</url>``

.. note::

    If you add a webspace to an existing installation you also have to set the
    correct permissions for existing users, otherwise they won't be able to see
    it.

As you probably already have encountered, the root tag for our webspace
definition is ``webspace``. Afterwards you see a name, which is displayed in the
administration interface. But even more important is the key, which is used
internally to generate some files and define some paths. Therefore it is really
important that the webspace key is unique across all webspaces in a single
installation.

Security (optional)
-------------------

The ``security`` tag allows to define a separate security system in its
``system`` tag. The security system will then be added as a possible option to
choose for the system of a user role. With this relation it is possible to
create roles specific to the Webspace's security system (see
:doc:`../../cookbook/securing-your-application` for more information about
security systems).

If the ``permission-check`` attribute of the ``security`` tag is set to
``true``, Sulu will automatically check if the current user has access to see
the requested page. It will also make sure that no pages are listed in the
website's navigation or in smart contents, if the user does not have the
necessary permissions.

.. note::

    Make sure caching is set up correctly if you use the security feature with
    the ``permission-check`` flag set to ``true``. The caching will only work
    if you have configured the :doc:`../cookbook/user-context-caching`.

Localizations
-------------

In the ``localizations``-tag you can list all the available localizations in this
webspace. In the example we are simply adding the English language, but you can
also define country specific language if you add a country attribute to the
localization, so for instance the following tag would add Austrian German to
the available localizations:

.. code-block:: xml

    <localization language="de" country="at" />

For a more complete explanation you should have a look at
:doc:`localization`.

Themes (optional)
-----------------

The ``theme`` is described by a key. This key leads to a certain theme,
implemented by a developer in the system. If you use multiple webspaces,
which should have a different look and feel, you can easily accomplish
this with the `SuluThemeBundle`_. Read more about the installation and usage
in the `bundle documentation`_.

Templates
---------

The webspace can also define certain templates in combination with a type.
These templates can then be retrieved from the webspace. E.g. Sulu uses them to
retrieve the correct templates for errors. Therefore it makes use of the
template with type ``error-<http-code>`` respectively it uses the template with
the type  ``error`` as a fallback. The other use case is the search. Sulu will
use the template with the type ``search`` from the webspace to display search
results.

Excluded templates (optional)
-----------------------------

The ``excluded-templates`` node defines which of the templates (the ones
described in :doc:`templates`) should be excluded in the template dropdown of
the page form. The entire node is optional, since especially if you only have
a single webspace this setting does not make a lot of sense.

Navigation
----------

It's also possible to define some so called navigation contexts, which allows
the user to add pages to different kind of navigations. The different contexts
can be defined in the ``navigation``-section, and this selection will be
available to choose from in the administration interface. Afterwards the
developer can retrieve the navigation for a given context by using some
Twig-extensions delivered with Sulu, whereby it is not only possible to
retrieve a flat list of pages, but also to retrieve entire navigation trees.

Segments (optional)
-------------------

For some website it makes sense to be displayed in multiple different segments.
A segment is defined in the above ``segments`` tag and the main part is giving
the segment a ``key``. Additionally a title for the segment to be displayed in
the UI is defined.

One of the segments must be set as the default. That's the segment a visitor
sees when visiting the website for the very first time. The visitor can switch
to a different segment in a similar way they can switch the localization. The
current segment will be stored in a cookie. Sulu also takes care of the cookie
when caching the website.

After configuring segments for a webspace the segments can be assigned to pages
in their "Excerpt & Taxonomies" tab. A page will then be hidden in navigation
and smart contents if the page has a segment assigned and another segment is
currently set for the visitor.

Resource-Locator (optional)
---------------------------

The URL of a page consists of the configured base URL of the webspace and a
page-specific ``resource-locator``. If the ``resource-locator`` of a page is changed,
Sulu will automatically redirect old URLs to the new URL per default. A webspace can set a
``strategy`` for managing the ``resource-locator`` of its pages.

The default strategy is ``tree_leaf_edit``, which means that the generated
``resource-locator`` of a page includes all ancestors in the content tree, but only
the last part will be editable. If the ``resource-locator`` of a page is changed, this
strategy will also update the ``resource-locator`` of all child pages.

The alternative ``tree_full_edit`` strategy also includes all ancestors when
generating the ``resource-locator``, but it allows to edit the whole ``resource-locator``
afterwards. If the ``resource-locator`` of a page is changed, this strategy does not update
the ``resource-locator`` of the child pages.

Portals
-------

A webspace can itself consist of multiple portals. In our simple configuration
file we make use of only one portal. The idea is that the same content can be
shared among different portals and URLs. The portals can then also define for
themselves in which localization they publish the content, so that you can
spread different localizations over different URLs.

Our sample file defines just one portal, which includes a ``name`` and a
``key`` just as the webspace, whereby the key for the portal hast to be unique
for the entire installation, not only within this webspace.

URLs
~~~~

The most important part of the portal configuration are the environments,
because they are including the URLs for the portal. A portal can have multiple
environments, which have to match the environments defined in Symfony. Usually
``dev``, ``stage`` and ``prod`` are available. Each environment can define its
own set of URLs.

.. note::

    Please consider that you have to omit the port in the configuration. The
    system will work with any port, so you don't have to name it in the
    configuration.

The URLs also have to include the localization somehow. You have two
possibilities to do so:

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
localization defined will be generated. So if you have a localization ``de-at``
and ``en-gb``, the system will create URLs for ``www.example.org/de-at`` and
``www.example.org/en-us``.

In the following table all the possible placeholders are listed, and explains
the values of them by using the ``de-at``-localization:

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

.. _SuluThemeBundle: https://github.com/sulu/SuluThemeBundle
.. _bundle documentation: https://github.com/sulu/SuluThemeBundle
