Versioning
==========

The versioning feature of Sulu will create a new version each time a page has
been published. Sulu will also show a table in the settings tab of each page,
showing the already created version. From this table old versions can be
restored, which will result in a new draft.

.. note::

   Mind that this feature is only available for :doc:`Jackrabbit <../../cookbook/jackrabbit>`, because Jackalope does
   currently not support versioning for its Doctrine DBAL transportation layer.

Configuration
-------------

The ``sulu_document_manager.versioning.enabled`` flag decides if versioning is
activated.

.. code-block:: yaml

    sulu_document_manager:
        versioning:
            enabled: true
