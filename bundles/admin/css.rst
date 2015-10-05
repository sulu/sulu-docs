Include custom css
==================

Sulu provides the package require-css_ to manage custom css. You can load css
files in any require component with the prefix `css!`. You have to omit the
file extension.

.. code-block:: javascript

    require.config({
        paths: {
            sulucontact: '../../sulucontact/js',
            sulucontactcss: '../../sulucontact/css'
        }
    });

    define(['css!sulucontactcss/main'], function(){});

This will include the css file and manage multiple usages. If you want to load
more than one css file simply add more entries in the array.

.. _require-css: https://github.com/guybedford/require-css
