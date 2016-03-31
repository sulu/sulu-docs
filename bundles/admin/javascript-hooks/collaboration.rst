Collaboration
=============

The collaboration-hook initializes collabation-component. This component is able
to communicate with the server and determine which user is currently working on
the same record.

Example
-------

.. code-block:: javascript

    return {
        collaboration: function() {
            return {
                id: this.options.id,
                type: 'example'
            };
        },

        initialize: function() {
            render();
        },

        ...
    }

This basic component initializes the collaboration and shows a warning when a
user edits the same record (example with the same id).
