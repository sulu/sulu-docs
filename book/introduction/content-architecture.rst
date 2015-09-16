Content Architecture
====================

Our content is heavily structured. As mentioned in :doc:`structure` we rely on the Symfony CMF on top of 
PHPCR. On this page we'll get into the Sulu Content Architecture.


Sulu Instance
-------------

At first you create a Sulu Instance. It could be seen as a **single 
installation** or one pool of data. Its real live mapping could be a company or 
an organization.


Webspace
--------

On your instance you'll define webspaces. Webspaces could represents your brands and corporations.
A landing page could be a single webspace.


Languages
---------

Once you created your webpsaces you could define languages. Pages could
then be translated or mapped to another language as **shadow-** or **ghost-pages**.


Page
----

As already heard in :doc:`backend-template` pages are configured in templates. They are created in
webspaces and represent an entry in a certain menue. This pages are structured within your
webspace. Once you got your :doc:`../getting-started/setup` right, you could easily explore the
Sulu default structure in the backend.


Content-Type
------------

A template is build up of several Content-Types. A blogpost for example could exisit of the 
following content-types:

* Single line text (*Title*)
* Multi line text (*Excerpt*)
* Richtext (*Content*)
* Date (*Creation-Date*)
* List of Tags (*Tags*)

A detailed list of all the content-types is covered in :doc:`../creating-a-basic-website/adding-a-template`.


Assets
------

Assets are media files like Images and Downloads. They are shared through all the Websites on the Instance.


Snippets
--------

Snippets are very simelar to assets. They are small pieces of content that could be shown on 
several pages in several webspaces.


Contacts
--------

Contacts reperesent personal information. It is a very generic type of as Sulu system users are also
represented through Contacts.


Whatever you want
-----------------

Sulu is very extensible. You got existing content you want to integrate through DBAL? Do it.
An API that delivers content? Integrate it. Other apps you've already coded. Use them.

A more detailled documentation could be found in the secion :doc:`../creating-a-basic-website/about-the-sulu-content-architecture`.

Now you are really deep into the concepts of Sulu. So the next steop is to get started.
:doc:`../getting-started/index`