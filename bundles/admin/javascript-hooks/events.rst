Events
======

The event-hook manages events and creates callable functions to throw or catch
aura events. It encapsulate the ' createEventName' function to build event-names
with the `this.events.namespace`, `this.options.instanceName` and `postFix` of
the given events.

.. code-block:: javascript

    define(['services/husky/util'], function (util) {

        'use strict';

        /**
         * Namespace for events.
         *
         * @type {string}
         */
        var eventNamespace = 'smart-content.categories.';

        return {

            defaults: {
                options: {instanceName: 'example}
            },

            events: {
                names: {
                    initialized: {postFix: 'initialized'},
                    getData: {
                        postFix: 'get-data',
                        type: 'on'
                    }
                },
                namespace: eventNamespace
            },

            initialize: function () {
                this.loadData().then(function (data) {
                    this.data = data;

                    // emit event
                    this.events.initialized(data);
                });

                // getter for data
                this.events.getData(function (callback) {
                    callback(this.data);
                }.bind(this));
            },

            loadData: function () {

                // ...

                return util.Deferred();
            }
        };
    });

.. note::

    The event-names will be build with this schema:
    `<namespace>.[<instancename>.]<postFix>`.

Types
-----

The events-hook can handle different types of events.

.. list-table::

    * - ``emit`` - **default**
      - Uses `this.sandbox.emit` to emit an aura event.
    * - ``on``
      - Uses `this.sandbox.on` to add a event-listener to a aura event.
    * - ``once``
      - Uses `this.sandbox.once` to add a one-time event-listener to a aura
        event.
