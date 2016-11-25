Creating a Page Template
========================

In Sulu, each page has a *page template*. The page template controls two things:

* the structure of the page
* how that structure is rendered

The structure of a page consists of *properties*, each of which has a *(content)
type*. The default page template, for example, defines the following
properties:

* **title** of type **text_line**
* **url** of type **resource_locator**
* **article** of type **text_editor**

When a content manager opens a page in the administration interface, they can
change the values of these properties. At last, frontend designers can access
these values and render them according to the desired design.

Each page template is defined by two files:

* an **XML file** that contains the page structure
* a **Twig file** that contains the HTML code

For example, the default template -- named "default" -- is defined by the files
``app/Resources/templates/pages/default.xml`` and
``app/Resources/views/templates/default.html.twig``. The Sulu Minimal Edition
also contains a second template named "homepage", which you can find in the
same directories.

This guide focuses on the configuration of the page structure in the XML file.
If you want to learn more about rendering the pages in Twig, read
:doc:`twig`.

Choosing the Template of a Page
-------------------------------

The template of a page can be selected in the admin interface:

.. figure:: ../img/templates-selection.png

.. Caution::

    A template is shown in the dropdown only if both the XML and the Twig file
    exist! If you can't see your template, double-check the directories
    ``app/Resources/templates/pages`` and ``app/Resources/views/templates``.

The name displayed in the dropdown is configured in the ``<meta>`` section of
the XML:

.. code-block:: xml

    <!-- app/Resources/templates/pages/default.xml -->
    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">
        <!-- ... -->

        <meta>
            <title lang="en">Default</title>
        </meta>

        <!-- ... -->
    </template>

You can customize the text by changing this property.

Creating a Custom Template
--------------------------

In your projects, you will need several templates for different parts of your
website. The easiest way is to copy and adjust the default template.

The first thing you need to adjust is the ``<key>``. This is the unique
identifier of the template:

.. code-block:: xml

    <!-- app/Resources/templates/pages/event.xml -->
    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">

        <key>event</key>

        <!-- ... -->
    </template>

.. caution::

    Currently the ``<key>`` has to be identical to the filename of the template
    minus the ``.xml`` suffix.

The second thing you have to customize is the ``<view>``. This element stores
the Twig file that is used to render the template:

.. code-block:: xml

    <!-- app/Resources/templates/pages/event.xml -->
    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">
        <!-- ... -->

        <view>ClientWebsiteBundle:templates:event</view>

        <!-- ... -->
    </template>

.. Note::

    The notation ``bundle:directory:filename`` is `Symfony's naming convention`_
    for Twig templates. Sulu automatically adds the ``.<format>.twig`` suffix
    to this string, depending on the format requested by the client
    (HTML, JSON, XML, ...).

We'll talk more about the Twig file itself in :doc:`twig`. Let's continue with
adding properties to our page template.

Properties
----------

Properties make up the structure of a page. They are defined in the element
``<properties>``:

.. code-block:: xml

    <!-- app/Resources/templates/pages/event.xml -->
    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">
        <!-- ... -->

        <properties>
            <!-- ... -->

            <property name="startDate" type="date">
                <meta>
                    <title lang="en">Start Date</title>
                </meta>
            </property>

            <!-- ... -->
        </properties>
    </template>

A property has three essential attributes:

* a ``name`` that is unique within a template
* a ``type`` that defines what kind of content can be stored
* a ``title`` that is shown in the administration interface

Here is a table with the content types shipped in Sulu core:

+-------------------------+---------------------------------------------+-----------------------------------------+
| Key                     | Appearance in the administration            | Value                                   |
+=========================+=============================================+=========================================+
| |text_line|             | simple text input                           | string                                  |
+-------------------------+---------------------------------------------+-----------------------------------------+
| |text_area|             | text area                                   | string                                  |
+-------------------------+---------------------------------------------+-----------------------------------------+
| |text_editor|           | text editor with formatting capabilities    | HTML string                             |
+-------------------------+---------------------------------------------+-----------------------------------------+
| |checkbox|              | checkbox                                    | boolean                                 |
+-------------------------+---------------------------------------------+-----------------------------------------+
| |single_select|         | list of radio buttons                       | string                                  |
+-------------------------+---------------------------------------------+-----------------------------------------+
| |multiple_select|       | list of checkboxes                          | array of strings                        |
+-------------------------+---------------------------------------------+-----------------------------------------+
| |color|                 | color picker                                | string                                  |
+-------------------------+---------------------------------------------+-----------------------------------------+
| |date|                  | date picker                                 | string                                  |
+-------------------------+---------------------------------------------+-----------------------------------------+
| |time|                  | text input with time validation             | string                                  |
+-------------------------+---------------------------------------------+-----------------------------------------+
| |url|                   | text input with URL validation              | string                                  |
+-------------------------+---------------------------------------------+-----------------------------------------+
| |email|                 | text input with email validation            | string                                  |
+-------------------------+---------------------------------------------+-----------------------------------------+
| |password|              | password input                              | string                                  |
+-------------------------+---------------------------------------------+-----------------------------------------+
| |phone|                 | text input for a phone number               | string                                  |
+-------------------------+---------------------------------------------+-----------------------------------------+
| |internal_links|        | widget for selecting links to other pages   | resolved pages as defined in parameters |
+-------------------------+---------------------------------------------+-----------------------------------------+
| |single_internal_link|  | widget for selecting a single page          | resolved page as defined in parameters  |
+-------------------------+---------------------------------------------+-----------------------------------------+
| |smart_content|         | widget for configuring a data source        | resolved pages as defined in parameters |
+-------------------------+---------------------------------------------+-----------------------------------------+
| |resource_locator|      | widget for entering the URL of a page       | string                                  |
+-------------------------+---------------------------------------------+-----------------------------------------+
| |tag_list|              | autocomplete input for entering and adding  | array of strings                        |
|                         | tags                                        |                                         |
+-------------------------+---------------------------------------------+-----------------------------------------+
| |category_list|         | autocomplete input for entering and adding  | array of strings                        |
|                         | tags                                        |                                         |
+-------------------------+---------------------------------------------+-----------------------------------------+
| |media_selection|       | widget for selecting media (images,         | array containing arrays with            |
|                         | documents)                                  | urls for every format                   |
+-------------------------+---------------------------------------------+-----------------------------------------+
| |contact_selection|     | widget for selecting contacts               | array containing array representations  |
|                         |                                             | of the contact objects                  |
+-------------------------+---------------------------------------------+-----------------------------------------+
| |teaser_selection|      | widget for displaying content teasers       | array containing array representations  |
|                         |                                             | of the teasers                          |
+-------------------------+---------------------------------------------+-----------------------------------------+
| |snippet|               | widget for selecting snippets               | array containing array representations  |
|                         |                                             | of the snippets                         |
+-------------------------+---------------------------------------------+-----------------------------------------+

.. tip::

    Use the command ``sulu:content:types:dump`` to list all the content types
    available in your project:

    .. code-block:: bash

        bin/adminconsole sulu:content:types:dump

Many content types can be configured by passing parameters in the element
``<params>``. For a single select, for example, you need to set the possible
choices:

.. code-block:: xml

    <!-- app/Resources/templates/pages/event.xml -->
    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">
        <!-- ... -->

        <properties>
            <!-- ... -->

            <property name="eventType" type="single_select">
                <meta>
                    <title lang="en">Event Type</title>
                </meta>
                <params>
                    <param name="values" type="collection">
                        <param name="concert">
                            <meta>
                                <title lang="en">Concert</title>
                            </meta>
                        </param>
                        <param name="festival">
                            <meta>
                                <title lang="en">Festival</title>
                            </meta>
                        </param>
                    </param>
                </params>
            </property>

            <!-- ... -->
        </properties>
    </template>

More detail about the content types and their parameters can be found in the
:doc:`../reference/content-types/index`.

Mandatory/Optional Properties
-----------------------------

Properties are optional by default. If a content manager *must* fill out a
property, set the attribute ``mandatory`` to ``true``:

.. code-block:: xml

    <!-- app/Resources/templates/pages/event.xml -->
    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">
        <!-- ... -->

        <properties>
            <!-- ... -->

            <property name="startDate" type="date" mandatory="true">
                <!-- ... -->
            </property>

            <!-- ... -->
        </properties>
    </template>

Sections
--------

Properties can be grouped together in *sections*. Sections are visible in the
administration interface only and have no other effect on the data model:

.. figure:: ../img/templates-section.png

A section is identified by its ``name``. This name is used for the anchor tag
in the administration interface.

As for properties, the label of the section goes into its ``<meta>`` tag:

.. code-block:: xml

    <!-- app/Resources/templates/pages/event.xml -->
    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">
        <!-- ... -->

        <section name="organizationalDetails">
            <meta>
                <title lang="en">Organizational Details</title>
            </meta>

            <!-- ... -->
        </section>

        <!-- ... -->
    </template>

The properties in the sections are nested in a separate element below the
section:

.. code-block:: xml

    <!-- app/Resources/templates/pages/event.xml -->
    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">
        <!-- ... -->

        <section name="organizationalDetails">
            <!-- ... -->

            <properties>
                <property name="startDate" type="date">
                    <meta>
                        <title lang="en">Start Date</title>
                    </meta>
                </property>
                <property name="endDate" type="date">
                    <meta>
                        <title lang="en">End Date</title>
                    </meta>
                </property>
            </properties>
        </section>

        <!-- ... -->
    </template>

Content Blocks
--------------

Similar to sections, content blocks contain a list of fields. In content blocks,
however, the content managers themselves can add fields of different types and
order them as they want:

.. figure:: ../img/templates-content-blocks.png

Content blocks are defined with the ``<block>`` element. Like properties, they
have a name that is used to access their content in Twig. The label of the
content block is -- you guessed it -- set in the ``<meta>`` element:

.. code-block:: xml

    <!-- app/Resources/templates/pages/event.xml -->
    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">
        <!-- ... -->

        <block name="eventDetails">
            <meta>
                <title lang="en">Event Details</title>
            </meta>

            <!-- ... -->
        </block>

        <!-- ... -->
    </template>

The content managers can choose the type of each individual block from a
dropdown. Attention, we're not talking about content types! The users of
the administration interface don't even know what the quite technical concept of
a content type is.

Instead, you should think about your own types that make sense in your case.
In this particular example, we want to provide the following types to our users:

* "Text" for formatted text
* "Image Gallery" for a gallery of images
* "Quote" for a quote from an artist

We'll define these types in the ``<types>`` element and set the default type in
the ``default-type`` attribute:

.. code-block:: xml

    <!-- app/Resources/templates/pages/event.xml -->
    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">
        <!-- ... -->

        <block name="eventDetails" default-type="text">
            <!-- ... -->

            <types>
                <type name="text">
                    <meta>
                        <title lang="en">Text</title>
                    </meta>

                    <!-- ... -->
                </type>
                <type name="imageGallery">
                    <meta>
                        <title lang="en">Image Gallery</title>
                    </meta>

                    <!-- ... -->
                </type>
                <type name="quote">
                    <meta>
                        <title lang="en">Quote</title>
                    </meta>

                    <!-- ... -->
                </type>
            </types>
        </block>

        <!-- ... -->
    </template>

Each of our types can be mapped to one or multiple properties. These properties
are shown in the administration interface when the content manager selects the
type:

.. code-block:: xml

    <!-- app/Resources/templates/pages/event.xml -->
    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">
        <!-- ... -->

        <block name="eventDetails" default-type="text">
            <!-- ... -->

            <types>
                <!-- ... -->

                <type name="quote">
                    <!-- ... -->

                    <properties>
                        <property name="text" type="text_area"/>
                        <property name="author" type="contact"/>
                    </properties>
                </type>
            </types>
        </block>

        <!-- ... -->
    </template>

.. note::

    The challenge here is to mentally separate *block types* from *content
    types*. You define *block types* yourself in the ``<types>`` element
    and set the default selection in ``default-type``. Only from the
    ``<property>``, we reference a *content type*.

Aligning Fields on the Grid
---------------------------

Sulu's administration interface uses a basic twelve-column grid for the
properties. By default, each property is all the twelve columns wide. If you
reduce that width, properties automatically float next to each other if they fit
within the twelve columns:

.. figure:: ../img/templates-aligned-fields.png

The width of a property is configured in the ``colspan`` attribute:

.. code-block:: xml

    <!-- app/Resources/templates/pages/event.xml -->
    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">
        <!-- ... -->

        <section name="organizationalDetails">
            <!-- ... -->

            <properties>
                <property name="startDate" type="date" colspan="6">
                    <!-- ... -->
                </property>
                <property name="endDate" type="date" colspan="6">
                    <!-- ... -->
                </property>
            </properties>
        </section>

        <!-- ... -->
    </template>

Help Text
---------

You can display a help text with additional information in properties and
sections. Put the help text into the ``<info_text>`` element in the ``<meta>``
section:

.. code-block:: xml

    <!-- app/Resources/templates/pages/event.xml -->
    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">
        <!-- ... -->

        <properties>
            <!-- ... -->

            <property name="endDate" type="date">
                <meta>
                    <!-- ... -->

                    <info_text lang="en">
                        If the same as the start date, the event is treated as
                        one-day event.
                    </info_text>
                </meta>
            </property>

            <!-- ... -->
        </properties>
    </template>

Including Other Templates
-------------------------

If you want to reuse a portion of a template in a different template, you can
move the portion to a separate file and include it using `XInclude`_.

.. warning::

   XInclude currently does not work on Windows.

To enable XInclude, we'll first add the namespace
``xmlns:xi="http://www.w3.org/2001/XInclude"`` to our document:

.. code-block:: xml

    <!-- app/Resources/templates/pages/event.xml -->
    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xmlns:xi="http://www.w3.org/2001/XInclude"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.1.xsd">

        <!-- ... -->

    </template>

Now we can include the fragment in the template with the ``<xi:include>``
element:

.. code-block:: xml

    <!-- app/Resources/templates/pages/event.xml -->
    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xmlns:xi="http://www.w3.org/2001/XInclude"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.1.xsd">

        <!-- ... -->

        <xi:include href="fragments/event-properties.xml"/>

        <!-- ... -->
    </template>

.. note::

    The ``href`` contains a relative path to the included file.

The fragment itself must contain a ``<template>`` or a ``<properties>`` element
as root. In this example, we'll use a ``<properties>`` container:

.. code-block:: xml

    <!-- app/Resources/templates/pages/fragments/event-properties.xml -->
    <?xml version="1.0" ?>
    <properties xmlns="http://schemas.sulu.io/template/template"
                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.1.xsd">

        <property name="startDate" type="date" mandatory="true">
            <!-- ... -->
        </property>

        <!-- ... -->
    </properties>

Including a Fragment of a Template
----------------------------------

If you want to pick single properties or sections of another template, use an
`XPointer`_. XPointers are similar to CSS selectors and match a specific part of
an XML document.

As example, imagine that you have a generic "Event" template and a more
specific "Concert" template that reuses the properties of the "Event" template.
Let's look at the "Event" template first:

.. code-block:: xml

    <!-- app/Resources/templates/pages/event.xml -->
    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xmlns:xi="http://www.w3.org/2001/XInclude"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.1.xsd">

        <!-- ... -->

        <properties>
            <!-- ... -->

            <property name="startDate" type="date" mandatory="true">
                <!-- ... -->
            </property>

            <!-- ... -->
        </properties>
    </template>

Nothing new here. To include these properties in the "Concert" template, pass
an XPointer that selects these elements in the ``xpointer`` attribute of the
``<xi:include>`` tag:

.. code-block:: xml

    <!-- app/Resources/templates/pages/concert.xml -->
    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xmlns:xi="http://www.w3.org/2001/XInclude"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.1.xsd">

        <!-- ... -->

        <properties>
            <!-- ... -->

            <xi:include href="event.xml"
                xpointer="xmlns(sulu=http://schemas.sulu.io/template/template)
                          xpointer(/sulu:properties/sulu:property)"/>

            <!-- ... -->
        </properties>
    </template>

The XPointer starts with the root element ``<properties>`` in the ``sulu``
namespace and selects all ``<property>`` children.

If you want to select an individual property with a specific name, that's
possible:

.. code-block:: xml

    <!-- app/Resources/templates/pages/concert.xml -->
    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xmlns:xi="http://www.w3.org/2001/XInclude"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.1.xsd">

        <!-- ... -->

        <properties>
            <!-- ... -->

            <xi:include href="event.xml"
                xpointer="xmlns(sulu=http://schemas.sulu.io/template/template)
                          xpointer(/sulu:properties/sulu:property[@name='startDate'])"/>

            <!-- ... -->
        </properties>
    </template>

This XPointer starts with the root element ``<properties>`` in the ``sulu``
namespace and selects all ``<property>`` children with the attribute ``name``
set to "startDate".

You can also match multiple elements of different types. Use the wildcard
``*`` for that:

.. code-block:: xml

    <!-- app/Resources/templates/pages/concert.xml -->
    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xmlns:xi="http://www.w3.org/2001/XInclude"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.1.xsd">

        <!-- ... -->

        <properties>
            <!-- ... -->

            <xi:include href="event.xml"
                xpointer="xmlns(sulu=http://schemas.sulu.io/template/template)
                          xpointer(/sulu:properties/*)"/>

            <!-- ... -->
        </properties>
    </template>

Caching
-------

Eventually you will start tweaking your pages for performance. Caching pages
on the client is one of the easiest performance improvements you can do.

You can configure a different caching strategy for each template. Add a
``<cacheLifetime>`` element with the number of seconds that your page should be
cached on the client:

.. code-block:: xml

    <!-- app/Resources/templates/pages/event.xml -->
    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">
        <!-- ... -->

        <cacheLifetime type="seconds">2400</view>

        <!-- ... -->
    </template>

The cache lifetime will be sent to the client in the ``max-age`` field of the
``Cache-Control`` header. After the specified time, the cache will be
invalidated on the client. The next time the page is requested, the client will
send a new request to your server to update its cache.

.. caution::

    When you use client-side caching, be aware that there is no way to
    invalidate the client-side cache on demand. Prepare for having to wait
    for the given cache lifetime until all clients receive an updated version
    of your website. To shorten this time, it's generally a good idea not to set
    the cache lifetime too high.

There is a second ``type`` that you can use to specify the cache lifetime:
``expression``. With that type, you can pass the lifetime as `cron expression`_.
For example, if you know that your homepage changes its content each day at
8:00 AM, set the value to ``0 8 * * *``:

.. code-block:: xml

    <!-- app/Resources/templates/pages/event.xml -->
    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">
        <!-- ... -->

        <cacheLifetime type="expression">0 8 * * *</view>

        <!-- ... -->
    </template>

Next Steps
----------

We learned a lot about configuring the structure of a page template. Continue
with :doc:`twig` to learn more about rendering this structure as HTML.

.. _Controller Naming Pattern: http://symfony.com/doc/current/book/routing.html#controller-string-syntax
.. _Template Naming and Locations: http://symfony.com/doc/current/book/templating.html#template-naming-locations
.. _XInclude: https://en.wikipedia.org/wiki/XInclude
.. _XPointer: https://en.wikipedia.org/wiki/XPointer
.. _Symfony's naming convention: http://symfony.com/doc/current/templating.html#template-naming-and-locations
.. _cron expression: https://github.com/mtdowling/cron-expression

.. |text_line| replace:: :doc:`text_line <../reference/content-types/text_line>`
.. |text_area| replace:: :doc:`text_area <../reference/content-types/text_area>`
.. |text_editor| replace:: :doc:`text_editor <../reference/content-types/text_editor>`
.. |color| replace:: :doc:`color <../reference/content-types/color>`
.. |date| replace:: :doc:`date <../reference/content-types/date>`
.. |time| replace:: :doc:`time <../reference/content-types/time>`
.. |url| replace:: :doc:`url <../reference/content-types/url>`
.. |email| replace:: :doc:`email <../reference/content-types/email>`
.. |password| replace:: :doc:`password <../reference/content-types/password>`
.. |phone| replace:: :doc:`phone <../reference/content-types/phone>`
.. |internal_links| replace:: :doc:`internal_links <../reference/content-types/internal_links>`
.. |single_internal_link| replace:: :doc:`single_internal_link <../reference/content-types/single_internal_link>`
.. |smart_content| replace:: :doc:`smart_content <../reference/content-types/smart_content>`
.. |resource_locator| replace:: :doc:`resource_locator <../reference/content-types/resource_locator>`
.. |tag_list| replace:: :doc:`tag_list <../reference/content-types/tag_list>`
.. |category_list| replace:: :doc:`category_list <../reference/content-types/category_list>`
.. |media_selection| replace:: :doc:`media_selection <../reference/content-types/media_selection>`
.. |contact_selection| replace:: :doc:`contact_selection <../reference/content-types/contact_selection>`
.. |teaser_selection| replace:: :doc:`teaser_selection <../reference/content-types/teaser_selection>`
.. |checkbox| replace:: :doc:`checkbox <../reference/content-types/checkbox>`
.. |multiple_select| replace:: :doc:`multiple_select <../reference/content-types/multiple_select>`
.. |single_select| replace:: :doc:`single_select <../reference/content-types/single_select>`
.. |snippet| replace:: :doc:`snippet <../reference/content-types/snippet>`
