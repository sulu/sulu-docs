Frontend routing
================

In Sulu's backend UI it is not like you click on a link and get redirected to a whole another page. Basically
there are only two main pages which you can redirect to in that sense that they load everything from start, namely
the admin- and the login-page.

For your own bundle you have to tell the frontend framework which route corresponds to which view. If a user then
navigates to the route you have configured, the frontend framework grabs your view-component and renders it in
the content-column. For defining routes we use `backbone.js <http://backbonejs.org/>`_.

The whole registering process is done in your :doc:`bundle-extension file </developer/create-bundle/frontend-structure>`.

Lets say we are a spaceship manufacturer and have written view-component in Resources/public/js/components/list/main.js
which renders a list of all spaceships contained in our system. To make our view appear when a user navigates to
``admin/#vehicles/spaceships`` we'd write the following into the bundle-extension:


.. code-block:: javascript

    // list all spaceships
    sandbox.mvc.routes.push({
        route: 'vehicles/spaceships',
        callback: function() {
            return '<div data-aura-component="list@suluvehicles"/>';
        }
    });
