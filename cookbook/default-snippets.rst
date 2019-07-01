How to define a default-snippet?
================================

Sulu gives the content-manager an easy way to define default snippets per type or area.
These default-snippets will be used if the snippet-selection is
empty on a specific page. This default selection will not be displayed in
the form and can have a big impact on the page.

Defining areas
--------------

Sulu allows you to to reuse the same snippet template to use it as default
for different snippet content types or load a snippet by its area key with
the twig extension. The areas can be defined in the xml template of the snippets.

.. code-block:: xml

    <key>sidebar</key>

    <meta>
        <title lang="en">Sidebar</title>
        <title lang="de">Sidebar</title>
    </meta>

    <areas>
        <area key="sidebar_default">
            <meta>
                <title lang="en">Sidebar Standard</title>
                <title lang="de">Sidebar Default</title>
            </meta>
        </area>

        <area key="sidebar_overview">
            <meta>
                <title lang="en">Sidebar Overview</title>
                <title lang="de">Sidebar Übersicht</title>
            </meta>
        </area>
    </areas>

.. note::

    When no areas are defined sulu automatically define an area per snippet template.


Configuration
-------------

The default snippets will be used if the following conditions are fulfilled.

1. The feature has to be activated (`sulu_snippet.types.snippet.default_enabled`) (default activated since 1.4)
2. The snippet-selection parameter ``default`` has to be
   defined. See :doc:`../reference/content-types/snippet_selection`.
3. The snippet for the configured area has to be selected in the
   webspace settings.

This conditions match the default snippet will be injected into the page.

Usage without the content type
------------------------------

To get the snippet for a specific area the developer can use the
twig-function :doc:`../reference/twig-extensions/functions/sulu_snippet_load_by_area`.
