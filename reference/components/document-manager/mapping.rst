Mapping
=======

.. warning::

    As of this version of Sulu we do not officially support user documents (documents
    made by you). It should work, however, we cannot guarantee backwards
    compatibility in future versions.

Encoding
--------

Sulu provides some fixed strategies for how properties *names* are *encoded*
within a PHPCR node.

For example, when storing a translated document, the PHPCR property name will
look as follows:

- ``lsys:en-myProperty``

Where ``myProperty`` is the name of the field in your document (or the
explicitly mapped property name), ``en`` is the locale and ``lsys`` is the
prefix (or namespace, more on this later).

This encoding is called ``system_localized`` and it should be used for system
only properties.

There are four encoding strategies in total:

- ``system``: Used for non-translated fields, e.g. ``nsys:status``.
- ``localized_system``: Used for translated system fields, e.g.
  ``lsys:de-title``.
- ``content``: Used for structure content.
- ``content_localized``: Used for translated structure content.

Mapping
-------

You can map fields in your application configuration under the 
the ``sulu_document_manager.mapping`` key.

For example:

.. code-block:: yaml

    sulu_document_manager:
        mapping:
            page:
                class: MyBundle\Document\PageDocument
                phpcr_type: sulu:page
                mapping:
                    title:
                        type: string
                        encoding: content_localized
                    body:
                        type: string
                        encoding: content_localized

Above we map the ``title`` and ``body`` fields of the document class
``MyBundle\\Document\\PageDocument``.

Mapping options are as follows:

- ``type``: Type of field, see below.
- ``property``: Name of the property to use when encoding to the PHPCR node.
- ``mapped``: If the value should be automatically mapped (in the case where
  you want to do this yourself).
- ``encoding``: The encoding strategy to use, see above.
- ``multiple``: Set to ``true`` if your field is an array.

Field Types
-----------

Scalar types:

- ``string``: Strings
- ``long``: Integers
- ``double``: Floating point number
- ``date``: Dates

Other types:

- ``reference``: Store document references.
