About the Sulu Content Architecture
===================================

We have already touched upon :doc:`introduction/content-architecture`
in the introduction.
Now that we are starting to code, we will dig a little deeper.

Sulu organizes content in webspaces, which have already been explained in the
section about the :doc:`introduction/components`. These webspaces contain an
arbitrary number of pages, which are ordered in a tree in a hierarchical way.
Each of these pages can contain content in many different localizations.

This tree also represents the actual structure of the website, so that no
additional navigation tree is required. Pages can be enabled in the navigation,
and will then appear in the right spot on the navigation of the website.

The pages in Sulu have a specific template applied. The template defines which
properties the page will have, whereby each of these properties are further
specified by a content type. The content type will have a direct impact on the
possible values and configuration possibilities of the property it is applied
to. There is also a further reference of all the available
:doc:`../reference/content-types/index`.

There are also some advanced features regarding the pages in Sulu. Besides the
content management using the properties and content types already described,
there is also the possibility to define internal and external links. Internal
links redirect to other pages managed by Sulu, while external links point to arbitrary URLs.

Another useful feature is the shadow page functionality. It allows you to use content from another localization.
For example, if a webspace defines localizations for
American and British English, you can use the American English content for British English, without duplicating content management.
This is especially useful if there are e.g. different contact addresses
for each country, but the rest of the page should be exactly the same.

With the content architecture in mind, we can move on to :doc:`webspaces`.
