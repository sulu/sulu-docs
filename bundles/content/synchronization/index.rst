Document Synchronization / Publishing
=====================================

Document synchronization is the act of explicitly synchronizing a document
from one source "workspace" to a target "workspace". In terms of this
component we transfer a document from one *source document manager* (SDM) to *target
document manager* (TDM).

One example where this might be useful is when you have one "manager" for the
live production site, and another for development / drafting site.

In this chapter you will create a "live" document manager which will be used
as the data source to render your website, whilst the "default" document
manager will be used as the data source for the administrative interface.

You will then be able to synchronize documents from the SDM to the TDM.

Configuration
-------------

First configure an additional document manager, here we will create a "live"
document manager:

.. code-block:: yaml

    sulu_document_manager:
        debug: false
        sessions:
            # ...
            live:
                backend:
                    type: "%phpcr.transport%"
                    url:  "%phpcr.backend_url%"
                username: "%phpcr.username%"
                password: "%phpcr.password%"
                workspace: live
        managers:
            # ...
            live:
                session: live

Note that we set the workspace to ``live``, but otherwise reuse the connection
settings for our ``default`` workspace.

.. important::

    Ensure that you use a different workspace for your new document manager -
    it would make no sense to have two document managers operating on the same
    data!

Now enable content synchronization:


.. code-block::

    sulu_content:
        # ...
        synchronize:
            target_document_manager: live

            default_mapping:
                ## for all documents
                auto_sync: [ "create", "update", "move", "delete" ]

            mapping:
                ## page documents
                Sulu\Bundle\ContentBundle\Document\BasePageDocument:
                    auto_sync: [ "delete", "move" ]
                    cascade_referrers:
                        - Sulu\Bundle\ContentBundle\Document\RouteDocument
                ## route documents
                Sulu\Bundle\ContentBundle\Document\RouteDocument:
                    auto_sync: [ ]
                    cascade_referrers:
                        - Sulu\Bundle\ContentBundle\Document\RouteDocument

.. info::

    ``BasePageDocument`` is the base class for all "pages" in the Sulu CMS.

Note the following:

1. We set tell the content bundle to synchronize to the ``live`` document
   manager;
2. Unless explicitly configured, we apply an default automatic synchronization
   policy to documents;
3. We explicitly state the auto synchronization policy for ``BasePageDocument`` and ``RouteDocument``
   classes;
4. We state cascade policies for ``BasePageDocument`` and ``RouteDocument``;

Finally we need to update our website configuration in order that it uses the
``live`` document manager:

.. code-block:: yaml

    # app/config/website/config/sulu.yml
    sulu_document_manager:
        default_manager: live

Initialization
--------------

Before being able to synchronize single documents, you need to initially
synchronize the entire document repository:

.. code-block::

    $ ./app/console sulu:document:sync-reset default live

This command will dump the content repository of the ``default`` manager,
*purge* the ``live`` content repository before loading the dump into it.

.. important::

    Pay attention when performing this operation, as the ``live`` (target)
    workspace will be completely purged, reversing the arguments will cause
    the inverse action to be performed (the ``default`` workspace will be
    purged and loaded with the contents of the live workspace).

Synchronization using the CLI
-----------------------------

You may synchronize documents using the following CLI command:

.. code-block:: bash

    $ ./app/console sulu:document:synchronize push --id=<path or uuid of node> --locale=de --force

.. note::

    Use the ``--help`` option on this command for detailed explanations of its
    options.

To synchronize a specific document in all locales:

.. code-block:: bash

    $ ./app/console sulu:document:synchronize push --id=/cmf/sulu_io/contents/foobar

To synchronize a specific document in a specific locale:

.. code-block:: bash

    $ ./app/console sulu:document:synchronize push --id=/cmf/sulu_io/contents/foobar --locale=de

By default the synchronization system will not synchronize a document which
thinks that it is already synchronized, you can override this behavior with
the ``--force`` option:

.. code-block:: bash

    $ ./app/console sulu:document:synchronize push --id=/cmf/sulu_io/contents/foobar --locale=de --force

In addition to "pushing" changes, you may also "pull" them from the TDM
SDM:

.. code-block:: bash

    $ ./app/console sulu:document:synchronize pull --id=/cmf/sulu_io/contents/foobar

Reference
---------

Auto synchronization policy
~~~~~~~~~~~~~~~~~~~~~~~~~~~

Documents can be configured to automatically synchronize depending on the
``auto_sync`` policy, the available options are as follows

- ``create``: Synchronize this document automatically when it is created.
- ``update``: Synchronize this document automatically when it is updated.
- ``remove``: Remove this document from the TDM when the
  document is removed from the SDM.
- ``move``: Move this document in the TDM when it is moved in the SDM.

Cascading
~~~~~~~~~

Cascading means that when, for example, a ``PageDocument`` is synchronized,
and it is configured to cascade to ``RouteDocument`` instances, any
``RouteDocument`` instances which reference the synchronized ``PageDocument``
will also be synchronized.

In the case of a ``PageDocument`` this is usually desired, as a page without
any routes is somewhat redundant.

Debugging
---------

The synchronization system will log debug information when the ``debug``
option is given

.. code-block:: 
    sulu_content:
        # ...
        synchronize:
            debug: true
            # ...
