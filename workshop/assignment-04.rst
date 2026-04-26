Assignment 4 — Add additional languages to the website
######################################################

The site should speak **English**, **German**, and **French**. You will register locales in the **webspace**, run Sulu’s
**initialize** command for new dimensions, adjust **portal URLs** so each language has an address, grant **permissions**,
translate content, and add a **language switcher** in the layout.

.. note::

   Excerpts follow ``assignment/04`` in the sulu-workshop repository.

What you learn
==============

* **Localizations** in the webspace. See :doc:`../book/localization`.
* The command ``sulu:document:initialize`` (or the equivalent in your Sulu version) to create the new language trees in
  the **content repository**.
* **URL patterns** per environment (e.g. ``{host}/{localization}``) in :doc:`../book/webspaces`.
* The ``localizations`` variable in Twig (often a list of locale + **url** for the same page in another language).

Prerequisites
=============

* Assignment 3 (or a branch with footer + navigation in place).

Step 1 — Declare languages in the webspace
==========================================

In ``config/webspaces/example.xml``, add ``<localization>`` entries. The reference keeps **en** as default and adds
**de** and **fr**:

.. code-block:: xml

    <!-- config/webspaces/example.xml (excerpt) -->
    <localizations>
        <localization language="en" default="true"/>
        <localization language="de"/>
        <localization language="fr"/>
    </localizations>

Step 2 — Initialize the content repository
==========================================

Run (from the project root; the workshop often uses the **admin** console for such commands):

.. code-block:: bash

   $ bin/adminconsole sulu:document:initialize

This bootstraps the new locales in the **PHPCR** content tree. If the command name differs in your Sulu 3 project, use
``bin/console`` and read the message from the assignment / README.

Step 3 — Map URLs to languages
==============================

The assignment changes the **portal** so each environment no longer only maps ``<url language="en">{host}</url>``, but
uses a path segment for the current **localization** (simplified in the reference):

.. code-block:: xml

    <!-- config/webspaces/example.xml (excerpt) -->
    <environments>
        <environment type="dev">
            <urls>
                <url>{host}/{localization}</url>
            </urls>
        </environment>
        <!-- stage, prod, test: same pattern in assignment/04 -->
    </environments>

.. tip::

   Exact URL strategy (prefix vs subdomain) is a product decision. See :doc:`../book/webspaces` for the attributes you
   can set on ``<url>`` (e.g. ``language``, ``localhost`` testing).

Step 4 — Grant permissions
===========================

In the admin, ensure your **admin** user (or the role you use) may **read and write** the new languages. The UI lives
under **Settings → User / Roles** (wording can vary by version).

Step 5 — Add translated content
===============================

Use the locale **switcher** in the page edit UI to add German and French titles and text for the same pages, or create
**shadows** as your workflow requires. See :doc:`../book/localization`.

Step 6 — Language switcher in the base template
===============================================

The Sulu request passes **localizations** into the layout. The reference **navbar** prints one link per locale; each
**localization** has at least **locale** and **url** (the URL of the *same* content in that language, when it exists):

.. code-block:: twig

    <!-- templates/base.html.twig (excerpt) -->
    <div class="navbar-nav">
        {% for localization in localizations %}
            <a class="nav-link" href="{{ localization.url }}">{{ localization.locale }}</a>
        {% endfor %}
    </div>

Use ``{{ dump(urls) }}`` or ``{{ dump(localizations) }}`` once while developing if you need to understand the array
shape for your Sulu version, then **remove** the ``dump`` calls.

See also
========

* :doc:`../book/localization`
* :doc:`../book/webspaces`
* `HTTP routing and the Request <https://symfony.com/doc/current/routing.html>`__

Reference branch
================

``assignment/04`` — diff ``config/webspaces/example.xml`` and ``templates/base.html.twig``.
