Frontend structure
==================

A lot of the folder structure on the backend's side of Sulu is determined by Symfony2. In the frontend however,
it leaves most things open. So here's introduction on how to arrange your frontend files and best-practice concepts
For the frontend everything takes place in your bundles Resources/public folder.

Resources/public folder
-----------------------

* **css** - holding the builded css files
* **dist** - holding the minified/uglified javascript files
* **img** - for putting image files
* **js** - here goes all of your javascript-code
* **scss** - the sass styling-files from which the css is built

Let's have a closer look on the **js** folder. Sulu tries to be convenient, fast and flexible in the user-interface. As
you probably have already noticed the page only loads once after the login. To provide such a interface beside
still being modular and preventing code duplication we must agree on a common architecture for the javascript code.

Resources/public/js folder
--------------------------

* **main.js** - This is the main entry point to your bundle's frontend. The bundle-extension file.
* **collections** - The folder where your backbone-collections go.
* **models** - Here are your backbone-models.
* **vendor** - If you need some vendors specific for your bundle, put them in this folder.
* **components** - In this folder you'll find the view- and the tab-components.
* **services** - In here go require-components which provide methods you'll need in your components. Like e.g. saving data or fetching data from the sever.
* **extensions** - Here you'll find files which are mainly required in your bundle-extension file to extend the frontend framework. Like for e.g. :doc:`sulu-buttons </bundles/admin/sulu-buttons>`.
* **validation** - If you are validating forms and have a custom element (not a standard input) which also should be validated, this is the place to put your validation files.

Bundle-extension file
---------------------

The bundle-extension file can be found within your bundle under Resources/public/js/main.js. Essentially this
file is an aura-extension which gets called right at the beginning when reloading the page. the bundle-extension is
used to register backbone-routes and :doc:`define which route corresponds to which view </developer/create-bundle/frontend-routing>`.
