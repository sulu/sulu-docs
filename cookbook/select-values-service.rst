Display values from a service in a select
=========================================

Sulu allows to set the value of a ``param`` of a property with the type ``expression``
via the `Symfony expression language`_.
This makes it possible to display values returned by a service in the case of the
:doc:`../reference/content-types/select` and :doc:`../reference/content-types/single_select` content-type.

.. note::

    Be aware that the provided expression is only evaluated during the initial request to the administration interface.
    If you want to provide a selection for your custom entity, you should configure the ``selection`` or ``single_selection`` field-type
    as described in :doc:`../../book/extend-admin`.

A service for returning the values for these content types could look something like this:

.. code-block:: php

    <?php

    namespace App\Content\Select;

    class EventTypeSelect {
        /**
         * @return array<int, array{name: string, title: string}>
         */
        public function getValues(): array
        {
            return [
                [
                    'name' => 'concert',
                    'title' => 'Concert',
                ],
                [
                    'name' => 'movie',
                    'title' => 'Movie',
                ],
            ];
        }

        /**
         * Optional default value for a single select.
         */
        public function getSingleSelectDefaultValue(): string
        {
            return 'concert';
        }

        /**
         * Optional default value for a multi select.
         *
         * @return array<int, array{name: string}>
         */
        public function getMultiSelectDefaultValue(): array
        {
            return [
                ['name' => 'concert'],
            ];
        }
    }

The service need to be `marked as public`_.
This is possible by adding the following lines to the ``config/services.yaml``:

.. code-block:: yaml

    App\Content\Select\:
        resource: '../src/Content/Select/'
        public: true

When your service is marked as public, you can access it in your template like this:

.. code-block:: xml

    <property name="eventType" type="single_select">
        <meta>
            <title lang="en">Event Type</title>
            <title lang="de">Event Typ</title>
        </meta>

        <params>
            <param name="default_value" type="expression" value="service('App\\Content\\Select\\EventTypeSelect').getSingleSelectDefaultValue()"/>
            <param name="values" type="expression" value="service('App\\Content\\Select\\EventTypeSelect').getValues()"/>
        </params>
    </property>

    <property name="eventTypes" type="select">
        <meta>
            <title lang="en">Event Type</title>
            <title lang="de">Event Typ</title>
        </meta>

        <params>
            <param name="default_values" type="expression" value="service('App\\Content\\Select\\EventTypeSelect').getMultiSelectDefaultValue()"/>
            <param name="values" type="expression" value="service('App\\Content\\Select\\EventTypeSelect').getValues()"/>
        </params>
    </property>

.. _Symfony expression language: https://symfony.com/doc/current/components/expression_language.html
.. _marked as public: https://symfony.com/doc/current/service_container/alias_private.html
