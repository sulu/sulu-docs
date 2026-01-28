Filter pages by a property using a custom SmartContent DataProvider
===================================================================

The ``smart_content`` property type allows for the configuration of a ``provider`` param, which is used for gathering
the items that are passed to the twig template. As described in :doc:`/reference/property-types/smart_content`, Sulu
comes with a few included providers and also allows to implement new ones to load custom entities.

In some cases, it might be useful to register an additional provider, which only returns pages including a property
with a specific value. This is possible by registering a new instance of the ``PageDataProvider`` included in Sulu
that uses a custom implementation of the ``QueryBuilder``. In the following example a custom ``AuthorPageDataProvider``
is implemented, which filters the pages for a specific author:

.. note::

    This documentation does not yet exist for Sulu 3.0. Please feel free to contribute it.
