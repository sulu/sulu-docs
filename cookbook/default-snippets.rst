Using default snippets for managing page-independent data
=========================================================

The :doc:`snippet feature<../bundles/snippet>` of Sulu allows to create and manage reusable pieces of content.
These pieces of content cannot only be assigned to a page using the ``snippet_selection``
property type, but they can also be associated to a webspace.

To do this, Sulu allows to define **snippet areas** which then can be assigned to a
**default snippet** on the ``Default snippets`` tab of the webspace in the administration
interface. After assigning default snippets to snippet areas via the administration
interface, Sulu provides different solutions for loading the data of the configured snippet
for a specific area and webspace.

This functionality is especially helpful to allow the content manager to managing
webspace-wide data such as social media links or contact information.

Defining possible areas for a snippet type
------------------------------------------

Each snippet template can define multiple snippet areas. All defined snippet areas will
be displayed on the ``Default snippets`` tab of the webspace in the administration interface
and can be assigned to a snippet that uses the respective template.

.. code-block:: xml

    <key>social_media_links</key>

    <meta>
        <title lang="en">Social Media Links</title>
    </meta>

    <areas>
        <area key="menu_social_media_links" cache-invalidation="true">
            <meta>
                <title lang="en">Menu Social Media Links</title>
            </meta>
        </area>

        <area key="footer_social_media_links" cache-invalidation="false">
            <meta>
                <title lang="en">Footer Social Media Links</title>
            </meta>
        </area>
    </areas>

For each area you can also define if the cache should be invalidated when either the snippet is
changed or the snippet assigned to the area is modified/removed. This can be done by setting the
``cache-invalidation`` attribute of the area to ``true`` or ``false``, the default value is ``true``.

.. note::

        Be aware that if a default-snippet is used in a base twig template, modifying the snippet or the
        area may result in a full cache invalidation of the website.

The area ``key`` must be unique across all snippet templates.

The ``Default snippets`` tab only exists when at least one snippet template defines an area. It is shown to users
whose role has the ``Edit`` permission for the ``sulu.webspaces.<webspace>.snippet-areas`` security context of at
least one webspace. Then it appears under every webspace. Grant the permission per webspace in the ``Webspaces``
section of the role permissions. Listing the areas of a webspace requires ``View``, assigning or removing a snippet
requires ``Edit`` for that webspace.

Loading the default snippet inside of a twig template
-----------------------------------------------------

Sulu includes a ``sulu_snippet_load_by_area`` twig function that allows to load the content of
the default snippet for a given area. The usage of the function is documented in
:doc:`/reference/twig-extensions/functions/sulu_snippet_load_by_area`.

Using the default snippet as fallback value in a ``snippet_selection``
----------------------------------------------------------------------

The ``snippet_selection`` property type can be configured to use the default snippet of a
specific area as fallback value when no snippet is selected. To do this, the ``default``
param needs to be set as described in :doc:`../reference/property-types/snippet_selection`.
