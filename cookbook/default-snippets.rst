How to define a default-snippet?
================================

Sulu gives the content-manager an easy way to define default snippets per type.
These default-snippets will be automatically used if the snippet-selection is
empty on a specific page. This default selection will will not be displayed in
the form and can have a big impact on the page.

.. note::

    This feature is disabled by default and can be activated by setting the
    config value `sulu_snippet.types.snippet.fallback_enabled` to true.

Configuration
-------------

The default snippets will be used if the following conditions are fulfilled.

1. The feature has to be activated
2. The snippet-selection parameter ``snippetType`` and ``default`` has to be
   defined. See :doc:`../reference/content-types/snippet`.
3. The default snippet for the configured type has to be selected in the
   webspace settings.

This conditions match the default snippet will be injected into the page.

Usage without the content type
------------------------------

To get the default snippet for a specific type the developer can use the
twig-function :doc:`../reference/twig-extensions/functions/sulu_snippet_load_default`.
