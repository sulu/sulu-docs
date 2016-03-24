How to override an analytics template?
======================================

You are able to override the analytics template with the
`symfony template overriding mechanism <http://symfony.com/doc/current/book/templating.html#overriding-bundle-templates>`_.

There are three relevant templates:

* ```SuluWebsiteBundle:Analytics/type/google.html.twig```
* ```SuluWebsiteBundle:Analytics/type/piwik.html.twig```
* ```SuluWebsiteBundle:Analytics/type/custom.html.twig```

You can access the following information in the twig variable ```analytics```.

.. list-table::
    :header-rows: 1

    * - Name
      - Type
      - Description
    * - id
      - int
      - A unique identifier of the analytics.
    * - title
      - string
      - The title of the analytics.
    * - allDomains
      - boolean
      - Indicates whether the analytics is on all domains of specific.
    * - content
      - mixed
      - Differs for the type.
    * - type
      - string
      - google / piwik / custom
    * - domains
      - array
      - Array of associated domains.

.. note::

    The ``content`` property contains for type google the key, for piwik an
    associated array of ``url`` and ``siteId`` and for the custom type the whole
    script (except the ``<script>`` tag).
