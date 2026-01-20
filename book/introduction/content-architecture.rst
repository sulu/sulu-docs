Content Architecture
====================

Our content is heavily structured. This page details the Sulu Content
Architecture.


Sulu Instance
-------------

First, you create a Sulu Instance. A Sulu instance can be seen as a **single
installation** or one pool of data. In real life, this could map to a company or
an organization.


Webspace
--------

In your instance, you will define webspaces. Webspaces could represent your brands
and corporations. A landing page could be a single webspace.


Languages
---------

Once you have created your webspaces, you could define languages. Pages could
then be translated or mapped to another language as **shadow-** or
**ghost-pages**.


Page
----

As mentioned in :doc:`backend-template` pages are configured in templates.
They are created in webspaces and represent an entry in a certain menu. These
pages are contained within your webspace. Once you have your
:doc:`setup <../getting-started>` configured correctly, you can start exploring the Sulu default
structure in the backend.


Property Type
-------------

A template is built from several property types. A blog post, for example, could
consist of the following property types:

* Single line text (*Title*)
* Multi line text (*Infobox*)
* Richtext (*Content*)
* Date (*Event-Date*)
* List of Tags (*Tags*)

A detailed list of all the property types is covered in
:doc:`../templates`.


Assets
------

Assets are media files like images and downloads. They are shared through all
the Websites on the instance.


Snippets
--------

Snippets are very similar to assets. They are small pieces of content that could
be shown on several pages in several webspaces.


Contacts
--------

Contacts represent personal information. They are also used to manage Sulu
Users itself.


Whatever you want
-----------------

Sulu is very extensible. Do you have existing content you want to integrate through
DBAL? Do it. An API that delivers content? Integrate it. Other Symfony bundles
that you have already coded? Integrate them.

A more detailed documentation can be found in the section
:doc:`../content-architecture`.

You have now dived deep into the concepts of Sulu. So the next step is to get
started. :doc:`../getting-started`
