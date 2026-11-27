Assignment 2 — Add a content block to the homepage
##################################################

A single **text editor** is flexible for editors but weak for **structured**, responsive layouts. In this task you add
a **block** field so editors can stack **typed** sections (text + image, quote, gallery) and reorder them, while the
theme keeps full control of the HTML.

.. note::

   Snippets below follow the reference branch ``assignment/02``.

What you learn
==============

* The **block** content type: multiple **types**, each with its own inner properties. See :doc:`../reference/property-types/block`.
* How **blocks** are exposed in Twig (``content.blocks``) and how to delegate to one partial per **type** using
  ``block.type``.
* Optional: **display options** for a field (e.g. image left/right) via ``<params>`` on the image property.

Prerequisites
=============

* Assignment 1 (or a branch that already has ``headerImage`` and the same base homepage).

Step 1 — Add a ``<block>`` in the template XML
===============================================

In ``config/templates/pages/homepage.xml``, blocks are **not** declared with a generic ``<property>``; you use a
``<block>`` element, set ``name`` and ``default-type``, and list each **type** under ``<types>``.

The reference adds a block named ``blocks`` with **three** types: ``text-image`` (title, article, image with
left/right), ``quote`` (text + author), and ``gallery`` (title + ``media_selection`` of images). Excerpt (middle of the
``text-image`` type and the end of the file):

.. code-block:: xml

    <!-- config/templates/pages/homepage.xml (excerpt) -->
    <block name="blocks" default-type="text-image">
        <types>
            <type name="text-image">
                <meta>
                    <title lang="en">Text image</title>
                    <title lang="de">Text image</title>
                </meta>
                <properties>
                    <property name="title" type="text_line">...</property>
                    <property name="article" type="text_editor">...</property>
                    <property name="image" type="single_media_selection">
                        <!-- ... -->
                        <param name="defaultDisplayOption" value="left"/>
                        <param name="displayOptions" type="collection">
                            <param name="left" value="true" />
                            <param name="right" value="true" />
                        </param>
                    </property>
                </properties>
            </type>
            <type name="quote">...</type>
            <type name="gallery">...</type>
        </types>
    </block>

List available property type names with:

.. code-block:: bash

   $ bin/console sulu:content:types:dump

Step 2 — Create content in the admin
====================================

Edit the **Homepage**, add several blocks, switch types from the dropdown, and reorder. Save and publish.

Step 3 — Loop and include partials by type
==========================================

**Pattern:** iterate ``content.blocks``; for each item, the **key** you need in Twig is ``block.type`` (e.g. ``text-image``,
``quote``, ``gallery``). The **view** may expose per-block view data under ``view.blocks[loop.index0]`` (used for
display options).

The reference centralizes the loop in ``includes/blocks.html.twig`` and dispatches to
``includes/block-types/<type>.html.twig`` (Twig can build the include name from ``block.type``; see
`Including templates`_ in the Twig documentation).

.. _Including templates: https://twig.symfony.com/doc/3.x/templates.html#including-other-templates

.. code-block:: twig

    {# templates/includes/blocks.html.twig #}
    {% for block in content.blocks %}
        <section class="container mt-5 clearfix">
            {% include "includes/block-types/#{block.type}.html.twig" with {
                content: block,
                view: view.blocks[loop.index0],
            } only %}
        </section>
    {% endfor %}

The homepage then **includes** that file and passes the nested ``content.blocks`` and ``view.blocks`` so the same
partial works for all types:

.. code-block:: twig

    <!-- templates/pages/homepage.html.twig (excerpt) -->
    {% include 'includes/blocks.html.twig' with {
        content: { blocks: content.blocks },
        view: { blocks: view.blocks },
    } only %}

A minimal **gallery** partial loads each **image** in the collection and reuses a small **200x**-style format key (ensure
the format exists in ``config/image-formats.xml`` or adjust to your project):

.. code-block:: twig

    {# templates/includes/block-types/gallery.html.twig #}
    <div class="row">
        <h3>{{ content.title }}</h3>
    </div>
    <div class="row">
        <div>
            {% for image in content.images %}
                <img src="{{ image.formats['200x'] }}" alt="{{ image.title }}" class="img-thumbnail m-3">
            {% endfor %}
        </div>
    </div>

See also
========

* :doc:`../book/twig`
* :doc:`../reference/property-types/block`
* `Twig include tag <https://twig.symfony.com/doc/3.x/tags/include.html>`__

Reference branch
================

``assignment/02`` — see ``config/templates/pages/homepage.xml``, ``templates/includes/blocks.html.twig``, and
``templates/includes/block-types/*.html.twig``.
