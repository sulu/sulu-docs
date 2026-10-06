Assignment 5 — Add an ``event_overview`` page template
######################################################

The website needs a dedicated **Events** overview: a normal **page** in the tree, but with a custom **template** that
lists all **Event** records via a **smart_content** property bound to the ``events`` data provider (already implemented in
the workshop project).

.. note::

   The XML and Twig below are from ``assignment/05``.

What you learn
==============

* Adding a new **page template** pair: ``config/templates/pages/<key>.xml`` and ``templates/pages/<view>.html.twig``.
* **Smart content** with a ``provider`` parameter. See :doc:`../book/smart-content` and
  :doc:`../reference/property-types/smart_content`.
* How the **workshop** exposes events to Twig (``event.resource`` fields on each item, depending on the provider
  implementation—use ``dump`` while learning).

Prerequisites
=============

* Earlier workshop steps (e.g. multilingual site from assignment 4 if you follow the cumulative branch).

Step 1 — Add ``event_overview.xml``
===================================

Create ``config/templates/pages/event_overview.xml`` with:

* ``<key>`` ``event_overview`` (must be unique),
* ``<view>`` ``pages/event_overview`` (maps to ``templates/pages/event_overview.html.twig``),
* the default **Sulu** website **controller** unless you already replaced it in a later task,
* standard page fields: **title**, **url** (``resource_locator`` with the usual RLP parts), **article** (intro),
* a property **events** of type **smart_content** with **provider** ``events``.

Reference file:

.. code-block:: xml

    <!-- config/templates/pages/event_overview.xml -->
    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">

        <key>event_overview</key>

        <view>pages/event_overview</view>
        <controller>Sulu\Bundle\WebsiteBundle\Controller\DefaultController::indexAction</controller>
        <cacheLifetime>86400</cacheLifetime>

        <meta>
            <title lang="en">Event Overview</title>
            <title lang="de">Veranstaltungsübersicht</title>
        </meta>

        <properties>
            <!-- title, url, article -->
            <property name="events" type="smart_content">
                <meta>
                    <title lang="en">Events</title>
                    <title lang="de">Veranstaltungen</title>
                </meta>
                <params>
                    <param name="provider" value="events"/>
                </params>
            </property>
        </properties>
    </template>

(The real file in the branch also includes **title** / **url** / **article** blocks; copy them from the **default** page
template if you start from scratch.)

Step 2 — Create the Twig view
=============================

.. code-block:: twig

    <!-- templates/pages/event_overview.html.twig -->
    {% extends "base.html.twig" %}

    {% block content %}
        <section class="jumbotron text-center">
            <div class="container">
                <h1 class="jumbotron-heading">{{ content.title }}</h1>
                <p class="lead text-muted">{{ content.article|raw }}</p>
            </div>
        </section>

        <div class="container marketing">
            <div class="row">
                {% for event in content.events %}
                    <div class="col-lg-4 text-center">
                        <h2 class="event-title">{{ event.resource.title }}</h2>
                        <p>{{ event.resource.teaser }}</p>
                        <p>
                            <a class="btn btn-secondary" href="{{ path('app.event', {id: event.id}) }}">
                                View details »
                            </a>
                        </p>
                    </div>
                {% endfor %}
            </div>
        </div>
    {% endblock %}

* ``content.events`` is the resolved list. Each item in this project exposes ``event.id`` and nested ``event.resource`` for
  the translated **title** / **teaser**; confirm with ``{{ dump(content.events) }}`` in development.

Step 3 — Create the page in the admin
=====================================

Create a new **page** (e.g. **Events**), choose the **event_overview** template, add intro text, **publish** it, and
attach it to the **main** navigation in **Page → Settings** if you want a menu link.

Step 4 — (Optional) Configure smart content
===========================================

In the admin, the **smart content** field may let editors filter or cap the number of events, depending on what the
``events`` data provider allows. The workshop provider typically lists **enabled** events; see
``EventDataProvider``/``EventRepository`` in the app.

See also
========

* :doc:`../book/templates`
* :doc:`../book/smart-content`
* :doc:`../cookbook/smart-content-data-provider` (for writing your *own* provider)

Reference branch
================

``assignment/05`` — new ``event_overview.xml`` and ``event_overview.html.twig``.
