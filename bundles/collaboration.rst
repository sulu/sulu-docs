CollaborationBundle
===================

This bundle activates the collaboration feature of Sulu. It shows a label, when
other content managers are currently editing the same record. It works with and
without websockets, whereby the version with websockets notifies the content
editors in real time. If websockets are not activated the first editor opening
the record will not see any notification in case another editor opens the
record. However, all the following editors will see a warning containing the
usernames of the editors having the record opened at the time.

The bundle saves this data in the cache, so that it is working in combination
with AJAX polling and even continues to work if the websockets has crashed in
between. The records are identified by a type, which is a simple string
uniquely describing the type of record (e.g. ``page`` or ``contact``) and the
identifier of the record.

The following code snippet shows how the collaboration feature can be activated
in any component:

.. code-block:: javascript

    collaboration: function() {
        if (!this.options.id) {
            return;
        }

        return {
            id: this.options.id,
            type: 'example'
        };
    }

The first statement checks if an id is set and returns early if not. This is
what we usually want, since no id indicates that a new record is created and
there will not be a conflict if two users are adding new records at the same
time.

The return value of the method is used by a JavaScript hook, which starts the
collaboration component with these options.

