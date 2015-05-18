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

- ``$creator``: The identifier creator of the object.
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

Mapping subscibers map information to properties in the document.

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
instances. Unmanaged parent documents with no UUID will **not be mapped**.

.. warning::

    This event currently inccurs a performance penalty as it needs to eagerly
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

**Behavior**: None. This bevavior is depends entirely on options.

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
