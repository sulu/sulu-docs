Backwards Compatibility Promise
===============================

Sulu is a stable software used in production. It is however still under heavy
development and therefore a full backwards compatibility can not be guaranteed
at the current stage.

We are do our best to keep backwards compatibility for the most used extension
points and services of Sulu. These are listed in this document.

The promises given in this document are only valid during a single major
release. When a new major version is released, these promises might be broken.

PHP
---

Twig
~~~~

The most important extension point is twig as its templates are used in any
project using Sulu for content management. We guarantee that the variables
passed to the twig template as described in
:doc:`../book/twig` will keep their
structure and that all the twig extensions as described in
:doc:`../reference/twig-extensions/index` will continue to work using the same
calls.

Configuration
~~~~~~~~~~~~~

There are several configuration files responsible for Sulu's behaviour for which
backwards compatibility is promised:

* Webspace (see :doc:`../book/webspaces`)
* Template (see :doc:`../book/templates`)
* Image formats (see :doc:`../book/themes`)
* MassiveSearch (see the `MassiveSearchBundle Mapping`_)
* Bundle configurations

Events
~~~~~~

Using events for extending Sulu is quite common so we will keep backwards
compatibility here. There might be new data added to some events but the current
data will not be removed. The events are also guaranteed not to change names.

Sulu-Admin
~~~~~~~~~~

Using the ``Admin`` class together with its navigation is guaranteed not to
break. The same is valid for extending some forms with custom tabs as described
in :doc:`../cookbook/using-tab-navigation`.

Content Types
~~~~~~~~~~~~~

It is safe to create custom content types by implementing the
``ContentTypeInterface`` or by inheriting one of the abstract
``SimpleContentType`` or ``ComplexContentType`` classes.

We also make sure that the content types delivered with Sulu will save the
content in the same way so that there will not be any regressions with the
content on an upgrade.

There will be migrations provided in case the structure of the content has to be
changed in order to fix some bugs and the previous promise cannot be kept.

Sulu Classes and Interfaces
~~~~~~~~~~~~~~~~~~~~~~~~~~~

The following classes and interfaces are guaranteed to keep backwards
compatibility:

* ``DocumentManagerInterface``
* ``WebsiteController``
* ``RequestAnalyzerInterface``
* ``SecurityCheckerInterface``
* ``User``
* ``Contact``
* ``Category``
* ``Tag``

JavaScript
----------

Structure
~~~~~~~~~

The main entry point will continue to exist at the same location
(``Resources/public/js/main.js`` of the bundle). Adding routes to the system via
this file will be continuing to work the same way. We will also continue to use
`RequireJS`_ and the components model including the ``sandbox``.

.. _MassiveSearchBundle Mapping: http://massivesearchbundle.readthedocs.org/en/latest/mapping.html
.. _RequireJS: http://requirejs.org/
