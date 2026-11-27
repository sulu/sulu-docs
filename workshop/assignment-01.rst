Assignment 1 — Add a header image to the homepage
#################################################

The homepage is mostly text. In this exercise you add a **header image** that editors select in the Sulu **admin**,
and you render it on the site using a named **image format** (so Sulu can scale and cache thumbnails).

.. note::

   The following snippets are taken from the reference branch ``assignment/01`` in the sulu-workshop repository.

What you learn
==============

* How a page **template** is split: XML (structure) + Twig (view). See :doc:`../book/templates` and :doc:`../book/twig`.
* The **single_media_selection** property. See :doc:`../reference/property-types/single_media_selection`.
* **Image formats** in ``config/image-formats.xml``. See :doc:`../book/image-formats`.
* How **content** and **thumbnails** appear in Twig (``content.headerImage`` exposes URLs under ``thumbnails``).

Prerequisites
=============

* A running workshop project and access to the admin (often ``admin`` / ``admin`` in local docs—use your README).

Step 1 — Declare the field in the page template
===============================================

Open ``config/templates/pages/homepage.xml`` and add a **property** named ``headerImage`` with type
``single_media_selection``. Restrict to images with ``<param name="types" value="image"/>`` so the picker only offers
suitable assets.

The reference solution adds the block **after** the URL / resource locator and **before** the main article, and keeps
other properties (e.g. **event** highlights) unchanged:

.. code-block:: xml

    <!-- config/templates/pages/homepage.xml (excerpt) -->
    <property name="headerImage" type="single_media_selection">
        <meta>
            <title lang="en">Header image</title>
            <title lang="de">Titelbild</title>
        </meta>

        <params>
            <param name="types" value="image"/>
        </params>
    </property>

Step 2 — Define the ``x400`` image format
===========================================

The exercise asks for a format key **x400**. Register it in ``config/image-formats.xml``. The workshop uses a
**scale** on the **y** axis to cap height at 400 px while keeping aspect ratio:

.. code-block:: xml

    <!-- config/image-formats.xml (excerpt) -->
    <format key="x400">
        <meta>
            <title lang="en">Header Image</title>
            <title lang="de">Titelbild</title>
        </meta>

        <scale y="400"/>
    </format>

Sulu generates the thumbnail the **first** time the URL is requested, then caches it. You can add more formats (the same
file also defines a wider **1920x** format for event images in later work).

Step 3 — Add media in the admin
===============================

#. Log in to the **administration** UI.
#. Open **Media** (or **Assets**, depending on wording).
#. Create a collection, e.g. **Header Images**, and upload a suitable file.
#. Edit the **Homepage** in the **content** tree, pick the new **header image** field, choose your file, then **Save and
   publish** (or your project’s publish workflow).

Step 4 — Render the image in Twig
=================================

In ``templates/pages/homepage.html.twig``, test the field with ``{{ dump(content) }}`` **once** to see the structure, then
output the image. After ``dump``, the resolved **single_media_selection** exposes **thumbnails** keyed by format
**name** (the ``key`` from the XML, here ``x400``):

.. code-block:: twig

    <!-- templates/pages/homepage.html.twig (excerpt) -->
    <section class="jumbotron text-center">
        <div class="container">
            {% if content.headerImage|default %}
                <img src="{{ content.headerImage.thumbnails['x400'] }}"
                     class="img-fluid"
                     alt="{{ content.headerImage.title }}"/>
            {% endif %}
            <h1 class="jumbotron-heading">{{ content.title }}</h1>
            <p class="lead text-muted">{{ content.article|raw }}</p>
        </div>
    </section>

Use ``|default`` (or a plain ``if``) so the template does not break when no image is selected.

Step 5 — Clear cache and verify
===============================

After changing XML or image format definitions, clear the application cache if thumbnails do not appear:

.. code-block:: bash

   $ bin/console cache:clear

(Use ``bin/adminconsole`` if your project documents admin-context commands for everything.)

.. tip::

   If something looks wrong, compare with :doc:`../book/image-formats` and ensure the format **key** in Twig exactly
   matches the ``<format key="...">`` in ``image-formats.xml``.

See also
========

* :doc:`../reference/property-types/single_media_selection`
* :doc:`../book/image-formats`
* `Twig template inheritance <https://symfony.com/doc/current/templates.html>`__

Reference branch
================

``assignment/01`` — compare ``homepage.xml``, ``image-formats.xml``, and ``templates/pages/homepage.html.twig``.
