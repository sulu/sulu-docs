Sulu-Buttons
============

The husky-toolbar-component needs to be passed data, which defines what buttons it should render or what happens
when somebody clicks on a button. Essentially Sulu-Buttons are about passing buttons to the toolbar-component in an
easier and more elegant way than just copying the same buttons all over the place.
With Sulu-buttons you can define buttons in a specific place, extend other buttons and override properties really easily.

Introduction
------------

The admin-bundle contains an aura-extension in which all default buttons are specified as well as methods to get buttons
and add buttons to the pool of default buttons.

An example for such a button is:

.. code-block:: javascript

    {
        name: 'save',
        template: {
            icon: 'floppy-o',
            title: 'public.save',
            disabled: true,
            callback: function() {
                app.sandbox.emit('sulu.toolbar.save', 'edit');
            }
        }
    }

As you can see a button gets registered with a name and a template, which is the actual button meeting the specifications
of the husky-framework.

The same holds for dropdown-items, for which also defaults are specified in the admin-bundle. For example:

.. code-block:: javascript

    {
        name: 'delete',
        template: {
            title: 'public.delete',
            callback: function() {
                app.sandbox.emit('sulu.toolbar.delete');
            }
        }
    }

Retrieve buttons
----------------

The aura-extension in the admin-bundle extends every sandbox of a javascript-component with the method
``sulu.buttons.get``. In your own component you can call this function like for example:

.. code-block:: javascript

    var generatedButtons = this.sandbox.sulu.buttons.get({
       edit: {},
       save: {
           options: {
               callback: function(){//do something//}
           }
       },
       settings: {
           options: {
               dropdownItems: {
                   delete: {}
               }
           }
       }
    });

The ``sulu.buttons.get`` method returns an array of buttons which meet the specification of the husky-framework. In
our example this array contains the template of the ``edit``-button, the template of the ``save`-button but with the
callback-property replaced with our own one and the template of the ``settings``-button which has the template of the
``delete``-dropdownItem as the only dropdown-item.

If you want the settings-button two times in the same toolbar with - let's say - different dropdown-items you can make
use of the ``parent`` property;

.. code-block:: javascript

    var generatedButtons = this.sandbox.sulu.buttons.get({
       settings1: {
           parent: 'settings'
           options: {
               dropdownItems: {
                   delete: {}
               }
           }
       },
       settings2: {
           parent: 'settings'
           options: {
               dropdownItems: {
                   table: {}
               }
           }
       }
    });

Add your own buttons
--------------------

Additionally to the ``sulu.buttons.get`` method the aura-extension provides the following methods:

* ``sulu.buttons.add``: takes a name and a button-template
* ``sulu.buttons.dropdownItems.add``: takes a name and a dropdownItem-template
* ``sulu.buttons.push``: takes an array of objects which all must contain a name and a template property
* ``sulu.buttons.dropdownItems.push``: takes an array of objects which all must contain a name and a template property
* ``sulu.buttons.getApiButton``: takes the name of a button-template and returns the actual template. Can be used to extend an existing button-template.

So with this methods you can easily add your own buttons and dropdown-items to the pool. These buttons are then
globally available via the ``sulu.buttons.get`` method.

When adding your own button the preferable place to specify them is in a requirejs-component named sulu-buttons.js within
the extensions-folder of your bundle. Adding the buttons and dropdown-items to the pool should then be done in the
js/main.js file of your bundle in which the sulu-buttons.js file is required.

If you want to specify your own button which extends another existing button you can do the following. In this example
the settings button is extended with a custom title.

.. code-block:: javascript

    var copyOfSettings = app.sandbox.sulu.buttons.getApiButton('settings');
    copyOfSettings.title = 'My own title';
    this.sandbox.sulu.buttons.add('my-settings-button', copyOfSettings);

.. note::
    Don't overuse the possibility to extend an existing button and provide a new one.
    Extending and providing your own button should only be done if the same button comes up in multiple places.
    If you just need to overwrite some properties of a default button in a single-place just use the ``sulu.buttons.get``
    method.
