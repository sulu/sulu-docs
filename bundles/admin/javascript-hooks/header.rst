Header
======

The header-hook renders a header (the blue bar with the toolbar in it) into your component.
Moreover it takes care of the tab-handling.

Most often you'll find yourself defining a component which contains the header-hook and
methods for manipulating the toolbar or other methods which implement behaviour which is
the same over all tabs.

The header-hook in such a component could look something like:

.. code-block:: javascript

    header: {
       tabs: {
           url: 'url/to/tabsData'
       },
       toolbar: {
           languageChanger: true,
           buttons: {
               save: {},
               settings: {
                   options: {
                       dropdownItems: {
                           delete: {}
                       }
                   }
               }
           }
       },
       title: 'My title'
    }

What this hook does is essentially the following:

* It initializes the tabs with the data returned from the defined url. From then on
  you'll never have to worry about tabs again. The whole routing to and starting your
  tabs component is handled automatically if the data returned by ``tabs.url`` has
  the right format.
* It initializes the toolbar with the defined buttons. For information on how to configure buttons
  and even how to create your own buttons have look :doc:`/bundles/admin/sulu-buttons`.
* It injects the title into every tab (or into your current component if no tabs specified)

.. note::
    The header-hook can also be a function which returns the object seen in the example. Within this function
    you have access to things like ``this.options``


List of all available options:
------------------------------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - title
      - String|Function
      - A title which gets injected into every tab-component or into the current component if no tabs exist. If it's
        a function it must return a string.
    * - noBack
      - Boolean
      - if true no back icon gets rendered
    * - tabs
      - Object
      - contains configuration-properties about the tabs
    * - tabs.url
      - String
      - url to load the tabs-data from
    * - tabs.data
      - Object
      - tabs-data. Either this option or the ``tabs.url`` option must be set when working with tabs.
    * - tabs.options
      - Object
      - an object which gets merged into the component-options of every tab-component
    * - tabs.container
      - String|Object
      - a selector or a dom-object into which the tabs-components get rendered
    * - toolbar
      - Object
      - contains configuration-properties about the toolbar
    * - toolbar.buttons
      - Object
      - an object of sulu-buttons. For the documentation on sulu-buttons have a look :doc:`/bundles/admin/sulu-buttons`.
    * - toolbar.options
      - Object
      - an object of options to pass to the husky-toolbar-component
    * - toolbar.languageChanger
      - Object|Boolean
      - contains configuration-properties for the language-changer dropdown. If just set to ``true`` renderes
        a dropdown with the system-locales and emits events as a callback
    * - toolbar.languageChanger.url
      - String
      - An url to load the items for the language-changer dropdown
    * - toolbar.languageChanger.callback
      - Function
      - a callback function which gets executed when the language-changer-dropdown gets changed
