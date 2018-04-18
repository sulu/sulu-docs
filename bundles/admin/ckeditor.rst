CKEditor
========

The build in editor CKEditor has a quite powerful plugin system. It can be
used to create additional toolbar-buttons and dialogs. Sulu gives you the
ability to add these as plugins to your bundle.

Example
-------

The code for this example was "borrowed" from
http://docs.ckeditor.com/#!/guide/plugin_sdk_sample_1.

1. Create a plugin file
***********************

.. code-block:: javascript

    define(function() {
        return function(sandbox) {
            return {
                // The plugin initialization logic goes inside this method.
                // http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.pluginDefinition.html#init
                init: function( editor )
                {
                    // Define an editor command that inserts an abbreviation.
                    // http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.editor.html#addCommand
                    editor.addCommand( 'abbrDialog',new CKEDITOR.dialogCommand( 'abbrDialog' ) );
                    // Create a toolbar button that executes the plugin command.
                    // http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.html#addButton
                    editor.ui.addButton( 'Abbr',
                    {
                        // Toolbar button tooltip.
                        label: 'Insert Abbreviation',
                        // Reference to the plugin command name.
                        command: 'abbrDialog',
                        // Button's icon file path.
                        icon: this.path + 'images/icon.png'
                    } );
                    // Add a dialog window definition containing all UI elements and listeners.
                    // http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.html#.add
                    CKEDITOR.dialog.add( 'abbrDialog', function ( editor )
                    {
                        return {
                            // Basic properties of the dialog window: title, minimum size.
                            // http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.dialogDefinition.html
                            title : 'Abbreviation Properties',
                            minWidth : 400,
                            minHeight : 200,
                            // Dialog window contents.
                            // http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.definition.content.html
                            contents :
                            [
                                {
                                    // Definition of the Basic Settings dialog window tab (page) with its id, label, and contents.
                                    // http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.contentDefinition.html
                                    id : 'tab1',
                                    label : 'Basic Settings',
                                    elements :
                                    [
                                        {
                                            // Dialog window UI element: a text input field for the abbreviation text.
                                            // http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.textInput.html
                                            type : 'text',
                                            id : 'abbr',
                                            // Text that labels the field.
                                            // http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.labeledElement.html#constructor
                                            label : 'Abbreviation',
                                            // Validation checking whether the field is not empty.
                                            validate : CKEDITOR.dialog.validate.notEmpty( "Abbreviation field cannot be empty" )
                                        },
                                        {
                                            // Another text input field for the explanation text with a label and validation.
                                            type : 'text',
                                            id : 'title',
                                            label : 'Explanation',
                                            validate : CKEDITOR.dialog.validate.notEmpty( "Explanation field cannot be empty" )
                                        }
                                    ]
                                },
                                {
                                    // Definition of the Advanced Settings dialog window tab with its id, label and contents.
                                    id : 'tab2',
                                    label : 'Advanced Settings',
                                    elements :
                                    [
                                        {
                                            // Yet another text input field for the abbreviation ID.
                                            // No validation added since this field is optional.
                                            type : 'text',
                                            id : 'id',
                                            label : 'Id'
                                        }
                                    ]
                                }
                            ],
                            // This method is invoked once a user closes the dialog window, accepting the changes.
                            // http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.dialogDefinition.html#onOk
                            onOk : function()
                            {
                                // A dialog window object.
                                // http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.html
                                var dialog = this;
                                // Create a new abbreviation element and an object that will hold the data entered in the dialog window.
                                // http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dom.document.html#createElement
                                var abbr = editor.document.createElement( 'abbr' );

                                // Retrieve the value of the "title" field from the "tab1" dialog window tab.
                                // Send it to the created element as the "title" attribute.
                                // http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dom.element.html#setAttribute
                                abbr.setAttribute( 'title', dialog.getValueOf( 'tab1', 'title' ) );
                                // Set the element's text content to the value of the "abbr" dialog window field.
                                // http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dom.element.html#setText
                                abbr.setText( dialog.getValueOf( 'tab1', 'abbr' ) );

                                // Retrieve the value of the "id" field from the "tab2" dialog window tab.
                                // If it is not empty, send it to the created abbreviation element.
                                // http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.html#getValueOf
                                var id = dialog.getValueOf( 'tab2', 'id' );
                                if ( id )
                                    abbr.setAttribute( 'id', id );

                                // Insert the newly created abbreviation into the cursor position in the document.
                                // http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.editor.html#insertElement
                                editor.insertElement( abbr );
                            }
                        };
                    } );
                }
            };
        };
    });

2. Register plugin
******************

In the main.js file of your bundle you can require the newly created plugin and
register it through the ``initialize`` function.

.. code-block:: javascript

    sandbox.ckeditor.addPlugin(
        'my-toolbar',  // which toolbar should include the button
        'Abbr',        // name of toolbar-button defined in the plugin
        'abbr',        // Name of plugin
        new Abbr(app.sandboxes.create('plugin-abbr'))
    );

You can now use your plugin in all Ckeditor instances.

.. note::

    To create your overlays or other plugins in our aura-system you can also
    use the sandbox to start your own component. But you have to
    be sure that you did stop all your components to reduce the memory-usage and
    calls to the event-system.
