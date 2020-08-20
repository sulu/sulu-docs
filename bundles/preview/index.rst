PreviewBundle
=============

The PreviewBundle provides the feature of preview for custom-entities.
It is build on top of the route-bundle and can only be introduced for
entities which have a `RouteDefaultsProvider`.

This Provider will be used to determine which controller should be
used to render the HTML of the entity.

.. toctree::
    :maxdepth: 2

    preview-cache
    preview-object-provider
