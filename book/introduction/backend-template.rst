Separation of Concerns
======================

As most of the modern Content Management Systems 
Sulu completely separates content from design. It urges
template based content rendering. Due to the fact that usability, web
standards and SEO are of such great importance the templates take a big role
in the creation of a Sulu based web platform.

To ensure that the templates make use of all the possibilities that the CMS
delivers, Sulu provides a set of functionalities and development guidelines.
Many of these can be found in this documentation and we strongly recommend to
carefully read through and use them as much as possible. By doing so your
content will be much easier to mange, the performance of your system will be
optimized and your website will be more accessible for users,  search engines
or third party applications.

Here are some of the spearations we did.

Template vs. Theme
------------------
As usual in Symfony the structure of data is separated from its presentation
layer. In Sulu the structure of a Page, a so called **Template** is defined.
The Template defines how the administration interface of the page looks and
how the data is passed to the theme. The **Theme** itself is - by default -
a set of **Twig Templates**.

We already heard some content sepcific terms on this page. On the next page
we'll have detailled look on the :doc:`content-architecture`.