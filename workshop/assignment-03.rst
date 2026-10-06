Assignment 3 — Add a footer navigation to the website
#####################################################

The **footer** is hard-coded. You will introduce a second **navigation context** called ``footer`` so editors can
attach pages to it, then render that context in the **base layout** so the footer updates without code changes.

.. note::

   The snippets below are from ``assignment/03`` in the sulu-workshop repository.

What you learn
==============

* **Webspace** configuration: the ``<navigation>`` section in ``config/webspaces/<name>.xml``. See
  :doc:`../book/webspaces`.
* The Twig helpers your project already uses for the **main** menu—often ``sulu_navigation_root_flat`` or
  ``sulu_page_navigation_root_tree``/``sulu_page_navigation_root_flat`` depending on the Sulu version. Check
  :doc:`../reference/twig-extensions/index` for the names that match your project.

  .. note::

     The reference workshop theme uses the legacy name ``sulu_navigation_root_flat``; newer docs list the
     **sulu_page_*** functions. If your theme already works for ``main``, reuse the **same** function and pass
     ``'footer'`` as the first argument (context key).

* How pages are linked to a context: **Page → Settings** (navigation contexts).

Prerequisites
=============

* Assignments 1–2, or a branch that includes their homepage changes.

Step 1 — Register the ``footer`` context
========================================

In ``config/webspaces/example.xml``, add another ``<context>`` under ``<navigation><contexts>`` **beside** ``main``:

.. code-block:: xml

    <!-- config/webspaces/example.xml (excerpt) -->
    <navigation>
        <contexts>
            <context key="main">
                <meta>
                    <title lang="en">Main Navigation</title>
                    <title lang="de">Hauptnavigation</title>
                </meta>
            </context>
            <context key="footer">
                <meta>
                    <title lang="en">Footer Navigation</title>
                    <title lang="de">Footernavigation</title>
                </meta>
            </context>
        </contexts>
    </navigation>

Step 2 — Tag pages in the admin
===============================

Create a few simple pages (e.g. **Impressum**, **Contact**) or reuse existing ones. In each page’s **Settings** tab,
enable the **footer** context so the page is eligible to appear in the footer list (alongside **main** if you want the
same page in both places).

Step 3 — Print the ``footer`` navigation in the base template
=============================================================

In ``templates/base.html.twig``, the **header** might already use something like ``sulu_navigation_root_tree('main')``
to build the top menu. For the **footer**, iterate the **footer** context the same way.

The reference implementation uses a **flat** list and **pipes** between items:

.. code-block:: twig

    <!-- templates/base.html.twig (footer excerpt, assignment/03) -->
    <footer class="footer mt-auto py-3">
        {% block footer %}
            <div class="container">
                {% for item in sulu_navigation_root_flat('footer') %}
                    <a href="{{ sulu_content_path(item.url) }}">{{ item.title }}</a>
                    {% if not loop.last %}&nbsp;|&nbsp;{% endif %}
                {% endfor %}

                <span class="text-muted float-right">Copyright {{ 'now'|date('Y') }} SULU</span>
            </div>
        {% endblock %}
    </footer>

* ``sulu_content_path`` builds the correct URL for the current webspace and language.
* ``loop.last`` avoids a trailing separator after the last link.

If you prefer a **nested** tree in the footer, use the tree variant for your Sulu version (see
:doc:`../reference/twig-extensions/index`).

See also
========

* :doc:`../book/webspaces`
* :doc:`../reference/twig-extensions/functions/sulu_page_navigation_root_flat` — if your project uses the ``sulu_page_``
  naming

Reference branch
================

``assignment/03`` — check ``config/webspaces/example.xml`` and ``templates/base.html.twig``.
