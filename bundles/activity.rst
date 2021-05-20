ActivityBundle
==============

The ActivityBundle implements is responsible for recording activities that happen in the application and allows
developer to listen for changes and other events that happen in the system.

Configuration
-------------

The sulu preview bundle can be configured the following way:

.. code-block:: yaml

    # config/packages/sulu_activity.yaml
    sulu_activity:
        storage:
            adapter: 'doctrine' # can be set to null to not store activities
            persist_payload: false # include payload of event in stored activity

Listen for an event
-------------------

TODO: describe this

Store a custom activity
-----------------------

TODO: describe this
