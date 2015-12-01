ContentRepository
=================

The content-repository was developed to query the content raw-data. The
developer can decide which properties will be loaded.

The main goal of the repository is to centralize the core features ghost, shadow
and internal links. These things will be handled full automatically. If you are
using this repository.

Usage
-----

There are two ways to use it. One in the front-end over an rest-api and one with
a backend service.

API
...

The URL of the API is `/admin/api/nodes` or `/admin/api/nodes/<uuid>`.

The mandatory parameters are:

.. list-table::

    * - Name
      - Example
      - Description
    * - locale
      - de
      - Localization which will be used.
    * - webspace
      - sulu_io
      - Webspace from which content will be loaded.
    * - fields
      - ''
      - Comma separated list of properties.

.. note::

   If the parameter fields is not set the content will be resolved with the slow
   legacy system (which is deprecated).

You can specify following optional parameters:

.. list-table::

    * - Name
      - Default
      - Description
    * - exclude-ghosts
      - false
      - If true ghost will be filtered.
    * - exclude-shadows
      - false
      - If true shadows will be filtered.

Over the mapping you can specify which properties will be loaded by the
repository (for example: 'title,order,article').

The list endpoint also accepts a `parent` parameter. If this parameter is set
the given page will be used to query for children else the webspace root is
default.

The single endpoint also accepts a `tree` flag. If it is true all parents and
siblings are returned else only the requested page will be returned.

Service
.......

The id of the service is `sulu_content.content_repository`. The methods can be
used as described in the phpdocs.

The mapping variable contains information for the mapping process.

.. list-table::

    * - Name
      - Description
    * - hydrateShadow
      - if this is false no shadow pages will be returned.
    * - hydrateGhost
      - if this is false no ghost pages will be returned.
    * - followInternalLink
      - if this is false the link will not be resolved.
    * - properties
      - List of hydrated properties

You can build this mapping over the mapping builder:

.. code-block:: php

    $mapping = MappingBuilder::create()
        ->setHydrateGhost()
        ->setHydrateShadow()
        ->addProperties(['title'])
        ->getMapping();
