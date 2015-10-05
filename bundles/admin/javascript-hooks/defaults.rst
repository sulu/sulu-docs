Defaults
========

The default-hook merges and prepares the options, translations and templates
automatically. This normalizes and centralize the need functionality of this
needs.

.. code-block:: javascript

    define(function () {

        'use strict';

        var defaults = {
            options: {
                instanceName: 'example',
                items: []
            },
            translations: {
                exampleButtonLabel: 'example.button.label',
                exampleHeader: 'example.header'
            },
            templates: {
                skeleton: [
                    '<h1><%= translations.exampleHeader %></h1>',
                    '<button><%= translations.exampleButtonLabel %></button>'
                ].join('')
            }
        };

        return {

            defaults: defaults,

            initialize: function () {
            },

            render: function () {
                this.dom.html(
                    this.templates.skeleton(
                        {translations: this.translations}
                    )
                );
            }
        };
    });

Options
-------

The `defaults.options` will be merged with `this.options` from aura.

Translations
------------

The `defaults.translations` will be merged with the `this.options.translations`
to overwrite translations of the component and translated with globalize.

Templates
---------

The `defaults.templates` will be merged with `this.options.templates` to
overwrite templates of the component and prepared with `_.template`.
