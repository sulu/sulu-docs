Sticky-Toolbar
==============

The sticky-toolbar extension enables the datagrid list toolbar to stick under
the header, when the page will be scrolled.

To enable this extension call set the property `stickyToolbar` to a non false
value. If this value is a number it will be used as the scroll-edge where the
toolbar will snap-in. You need this for example if you use tabs in your
component to compensate the height of the tabs container (90 + 50 = 90 for the
header and 50 for the tabs-container).

.. code-block:: javascript

    {
        stickyToolbar: true // or a number like: 140
    }

To reset the scroll-container if you rerender your component you can call the
`this.sandbox.stickyToolbar.reset()` method.
