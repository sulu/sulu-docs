View
====

The view-hook is just a boolean flag which resets the layout to its default values (if no
own layout-hook is set).

The defaults of the layout-hook are:

.. code-block:: javascript

    layout: {
        navigation: {
            collapsed: false,
            hidden: false
        },
        content: {
            width: 'fixed',
            leftSpace: true,
            rightSpace: true,
            topSpace: true
        },
        sidebar: false
    }
