Behaviors
=========

The document manager interacts with objects (documents) with event
subscribers. The event subscribers use interfaces to determine if they should
apply themselves to a document. These interfaces are known as *Behaviors*.

This chapter will explain all of the behaviors which are available in Sulu.

Each section will show, according to need, the **behavior** interface required to
implement the behavior, the **properties** which you MUST implement and any
**options** which will be available.

Auditing
--------

Auditing subscribers record and provide access to details of when the document
was modified and the user that modified it.

Blame
~~~~~

**Behavior**: ``Sulu\Component\DocumentManager\Behavior\BlameBehavior``

This behavior will record the identifier of the user who created the object
and when the document is updated, the user who updated it.

The user identifier will be retrieved from the Symfony session, or can be
explicitly specified with an option.

If no user identifier is available, no action will be taken.

**Properties**:

- ``$creator``: The identifier of the user that created the object.
- ``$changer``: The identifier the last user to have changed the object.

**Options**:

- ``blame.user_id``: Specify or override the identifier which will be
  recorded.

Timestamp
~~~~~~~~~

**Behavior**: ``Sulu\Component\DocumentManager\Behavior\Audit\TimestampBehavior``

Record the time when the object was created and the time when the object was
updated.

**Properties**:

- ``$created``: The date the object was created.
- ``$changed``: The data the object was changed.

Mapping
-------

Mapping subscribers set and provide access to properties in the document.

Children
~~~~~~~~

**Behavior**: ``Sulu\Component\DocumentManager\Behavior\Mapping\ChildrenBehavior``

Provides access from the document to the children of the document in the
content tree. The property will be populated with a collection object.

.. note::

    Children documents are loaded lazily, so this behavior does not entail a
    performance penalty.

**Properties**:

- ``$children``: The children collection.

Locale
~~~~~~

**Behavior**: ``Sulu\Component\DocumentManager\Behavior\Mapping\LocaleBehavior``

Provides access to the documents Locale at the time the object was hydrated
according to the ``DocumentRegistry``.

**Properties**:

- ``$locale``: The locale the document is currently loaded in.

NodeName
~~~~~~~~

**Behavior**: ``Sulu\Component\DocumentManager\Behavior\Mapping\NodeNameBehavior``

Maps the PHPCR node name.

**Properties**:

- ``$nodeName``: The locale the document is currently loaded in.

Parent
~~~~~~

**Behavior**: ``Sulu\Component\DocumentManager\Behavior\Mapping\ParentBehavior``

Map and assign the parent document through ``getParent`` and ``setParent``
methods.

Unmanaged parent documents with a UUID will be returned ``UnknownDocument``
instances. Unmanaged parent documents with no UUID will **not be hydrated**
(they will be ``NULL``).

.. warning::

    This event currently incurs a performance penalty as it needs to eagerly
    load the parent PHPCR node. This could potentially have a noticable impact when
    loading a large number of nodes.

Path
~~~~

**Behavior**: ``Sulu\Component\DocumentManager\Behavior\Mapping\PathBehavior``

Map the path of the document within the content repository.

**Properties**:

- ``$path``: The path to the document.

Title
~~~~~

**Behavior**: ``Sulu\Component\DocumentManager\Behavior\Mapping\TitleBehavior``

Map the title of the document.

**Properties**:

- ``$title``: Title of the document.

Uuid
~~~~

**Behavior**: ``Sulu\Component\DocumentManager\Behavior\Mapping\UuidBehavior``

Map the UUID (Universally Unique Identifier) of the document.

**Properties**:

- ``$uuid``: The UUID of the document.

Path
----

Path subscribers affect the location of the document within the content
repository.

AliasFiling
~~~~~~~~~~~

**Behavior**: ``Sulu\Component\DocumentManager\Behavior\Path\AliasFilingBehavior``

This is a filing behavior which will automatically place the document at given
path as a child of a node named after the documents *alias* as defined in the
configuraiton mapping.

For example, if the base path is ``/cms/content`` and the document has an alias
of ``article`` and the name ``my-article`` then the document will be stored at
``/cms/content/article/my-article``.

AutoName
~~~~~~~~

**Behavior**: ``Sulu\Component\DocumentManager\Behavior\Path\AutoNameBehavior``

The auto-name subscriber will automatically set the node name of the PHPCR
node as a *slugified* version of its title (the document must also implement
the ``TitleBehavior``).

Explicit
~~~~~~~~

**Behavior**: None. This behavior is depends entirely on options.

This subscriber allows the path of the document to be set explicitly through
the use of options. This subscriber requires no interfaces, it is available on
all documents automatically.

For example:

.. code-block:: php

    <?php
    $documentManager->persist($document, 'de', array(
        'path' => '/path/to/document'
    ));

**Options**:

- ``path``: Absolute path to where the document should be stored.
- ``parent_path``: Specify only the parent path (the node name could then be
  determined through another mechanism, e.g. the ``AutoName`` behavior.
- ``node_name``: Specify only the node name
- ``auto_create``: If any "missing" parent nodes should be automatically
  created.

Sulu Specific
-------------

The following behaviors are specific to Sulu.

Content
~~~~~~~

**Behavior**: ``Sulu\Component\Content\Document\Behavior\ContentBehavior``

Maps the structure content to the document. The content is mapped as a
``ContentContainer`` instance.

**Properties**:

- ``$content``: The content container.

Extension
~~~~~~~~~

**Behavior**: ``Sulu\Component\Content\Document\Behavior\ExtensionBehavior``

Sets and provides access to the extension data.

LocalizedContent
~~~~~~~~~~~~~~~~

**Behavior**: ``Sulu\Component\Content\Document\Behavior\LocalizedContentBehavior``

Allows the document to potentially have different structure type for each locale.

NavigationContext
~~~~~~~~~~~~~~~~~

**Behavior**: ``Sulu\Component\Content\Document\Behavior\NavigationContextBehavior``

Enables the document to have navigation contexts assigned to it.

Order
~~~~~

**Behavior**: ``Sulu\Component\Content\Document\Behavior\OrderBehavior``

Documents implementing this behavior will have a ``sulu:order`` property added
to the PHP node which will enable the document the order to remain constant in
both the tree  and in query results.

Page
~~~~

**Behavior**: ``Sulu\Component\Content\Document\Behavior\PageBehavior``

Documents implementing this behavior will be treated as "pages" - that is they
are expected to represent a single webpage with an associated route.

This behavior extends the Webspace behavior.

RedirectType
~~~~~~~~~~~~

**Behavior**: ``Sulu\Component\Content\Document\Behavior\RedirectTypeBehavior``

Documents implementing this behavior are able to optionally redirect to either
an internal or an external resource.

ResourceSegmentBehavior
~~~~~~~~~~~~~~~~~~~~~~~

**Behavior**: ``Sulu\Component\Content\Document\Behavior\ResourceSegmentBehavior``

Maps a resource segment which will be used when generating the URI for the
document.

Route
~~~~~

**Behavior**: ``Sulu\Component\Content\Document\Behavior\RouteBehavior``

Documents implementing this behavior will act as routes. Routes are documents
which are located at a path representing one of the URIs of a page document.
The route contains a reference to the page.

ShadowLocale
~~~~~~~~~~~~

**Behavior**: ``Sulu\Component\Content\Document\Behavior\ShadowLocaleBehavior``

The implementing document will have the possiblity to enable a "shadow
locale" and load its content from a different locale within the same document.

StructureTypeFiling
~~~~~~~~~~~~~~~~~~~

**Behavior**: ``Sulu\Component\Content\Document\Behavior\StructureTypeFilingBehavior``

Implementing documents will be stored at a path depending on their structure
type. Snippets implement this behavior.

Webspace
~~~~~~~~

**Behavior**: ``Sulu\Component\Content\Document\Behavior\WebspaceBehavior``

Provides access to the documents webspace name.

WorkflowStage
~~~~~~~~~~~~~

**Behavior**: ``Sulu\Component\Content\Document\Behavior\WorkflowStageBehavior``

Documents implementing this interface can have a workflow stage applied to
them. For example "test" and "published" are workflow stages.
