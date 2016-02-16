Separation of Concerns
======================

Like most modern Content Management Systems, Sulu completely separates content
from design. It urges template based content rendering. Since usability, web
standards and SEO are of such great importance, templates take a big role
in the creation of a Sulu based web platform.

To ensure that templates make use of all the possibilities that the CMS
provides, Sulu includes a set of tools and development guidelines.
Many of these can be found in this documentation and we strongly recommend to
carefully read through and use them as much as possible. By doing so, your
content will be much easier to manage, the performance of your system will be
optimized, and your website will be more easily accessible for users, search
engines and third party applications.

Here are some of the separations we did.

Template vs. Theme
------------------

As usual in Symfony, the structure of data is separated from its presentation.
In Sulu, the structure of a page, a so called **template**, is defined.
The template describes how the administration interface of the page looks and
how the data is passed to the theme. The **theme** itself is - by default -
a set of **Twig templates**.

We already heard some content specific terms on this page. On the next page
we'll have detailed look at the :doc:`content-architecture`.
