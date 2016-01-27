Views and tabs
==============

Views and tabs are stored under Resources/public/js/components. A view is a javascript-component which
gets :doc:`registered in combination with a route </developer/create-bundle/frontend-routing>`.

Main-views
----------

Essentially you're free to render and do whatever you want in a view-component. Yet you'll most often
find yourself doing the following things with a view-component.

#. Using the :doc:`header-hook </bundles/admin/javascript-hooks/header>` to get a neat little header rendered into your view component
#. Writing methods which implements behaviour which stays the same over all tabs
#. Communicating with your tab-components via events.

Tab-views
---------

When using the :doc:`header-hook </bundles/admin/javascript-hooks/header>` you'll also have the
possibility to register tabs. Tabs are nothing more and nothing less than again javascript-components. The files
for the tab-components get created in sub-folders of the main-view. So lets say we have a main view under
Resources/public/js/components/list. After adding a details-tab and a settings tab, the structure in the list folder
looks the following

* **main.js** - This is the main-view where the header is rendered via the :doc:`header-hook </bundles/admin/javascript-hooks/header>`
* **details/main.js** - The js-component of the details-tab
* **settings/main.js** - The js-component of the settings-tab
