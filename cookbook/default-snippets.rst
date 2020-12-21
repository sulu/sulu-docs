Using default snippets for managing page-independent data
=========================================================

The snippet feature of Sulu allows to create and manage reusable pieces of content.
These pieces of content cannot only be assigned to a page using the ``snippet_selection``
content type, but they can also be associated to a webspace.

To do this, Sulu allows to define **snippet areas** which then can be assigned to a
**default snippet** on the ``Default Snippets`` tab of the webspace in the administration
interface. After assigning default snippets to snippet areas via the administration
interface, Sulu provides different solutions for loading the data of the configured snippet
for a specific area and webspace.

This functionality is especially helpful to allow the content manager to managing
webspace-wide data such as social media links or contact information.

Defining possible areas for a snippet type
------------------------------------------

Each snippet template can define multiple snippet areas. All defined snippet areas will
be displayed on the ``Default Snippets`` tab of the webspace in the administration interface
and can be assigned to a snippet that uses the respective template.

.. note::

    If no snippet template of the application defines an area, Sulu automatically creates
    an area for each snippet template using the title and key of the template.

.. code-block:: xml

    <key>social_media_links</key>

    <meta>
        <title lang="en">Social Media Links</title>
    </meta>

    <areas>
        <area key="menu_social_media_links">
            <meta>
                <title lang="en">Menu Social Media Links</title>
            </meta>
        </area>

        <area key="footer_social_media_links">
            <meta>
                <title lang="en">Footer Social Media Links</title>
            </meta>
        </area>
    </areas>

Loading the default snippet inside of a twig template
-----------------------------------------------------

Sulu includes a ``sulu_snippet_load_by_area`` twig function that allows to load the content of
the default snippet for a given area. The usage of the function is documented in
:doc:`/reference/twig-extensions/functions/sulu_snippet_load_by_area`.

Using the default snippet as fallback value in a ``snippet_selection``
----------------------------------------------------------------------

The ``snippet_selection`` content type can be configured to use the default snippet of a
specific area as fallback value when no snippet is selected. To do this, the ``default``
param needs to be set as described in :doc:`../reference/content-types/snippet_selection`.

