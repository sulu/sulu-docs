Context Reference
=================

Behat matches sentences to methods in ``Context`` classes in order to execute
things. For example the sentence ``I click on foo`` might map to the method
``DefaultContext#iClickFoo``.

Context classes can be found in the namespace
``Sulu\Bundle\<BundleName>\Behat``. Each one contains the logic which applies
directly to the bundle it belongs to, as such you will probably never need all
of them.

This chapter will outline the contexts which you will want to use frequently.

AdminContext
------------

**Bundle**: SuluAdminBundle

This context contains all actions which apply to the backoffice, including all
actions which apply to *Husky* components.

The following is not a fully comprehensive list:

- ``I expect a success notification to appear``: Wait and assert that a success
  notification will appear.
- ``I expect a data grid to appear``: Wait for and assert that a husky
  datagrid appears
- ``I expect a form to appear``: Wait for any ``<form/>`` to appear on the
  page.
- ``I expect an overlay to appear``: Wait and assert that an overlay appears
  on the page.
- ``I click the tick button``: Click the tick button in a dialog
- ``I confirm``: Confirm a dialog
- ``I click the search icon``: Click the search icon (``.btn .fa-search``)
- ``I click the row containing ":text"``: Click on the husky table row
  containing "text".
- ``I select :value from the husky :name``: Select the given value from
  a husky drop-down selector as identified by selector.
- ``I fill in husky field ":name" with ":value"``: Fill in a husky field (husky
  fields do not have ``name`` elements preventing us from using the default
  mechanism).
- ``I click the button ":text"``: Click the button containing the text ``:text``
- ``I click toolbar item ":id"``: Click on the toolbar item with given id (also dropdown)
- ``I expect the ":event" event``: Wait for the named aura event
- ``I expect the following events``: Wait until all of the given events have
  been emitted.
- ``There should be errors``: Assert that there are errors on the page.
- ``I wait for the ajax request``: Wait until all underlying AJAX requests
  have finished.
- ``I click the close icon``: Click the close icon (``.fa-times``)
- ``I click the close icon in container ":selector"``: Click the close 
  icon (``selector + ' .fa-times'``)
- ``I wait for the column navigation column :index``: Wait for the column 
  navigation column with given id.
- ``I expect a data-navigation to appear`` or 
  ``I expect wait for data-navigation to appear``: Wait and assert that a 
  data-navigation will appear.
- ``I expect an overlay to appear``: Wait and assert that a overlay
  will appear.

ContentContext
--------------

**Bundle**: SuluContentBundle

You will use the content context frequently when testing content types.

- ``there exists a page template ":name" with the following property
  configuration``: Create a page template with a given content type
  configuration:

.. code-block:: cucumber

    Background:
        Given there exists a page template "checkbox_page" with the following property configuration
        """
        <property name="checkbox" type="checkbox">
            <meta>
                <title lang="de">Checkbox</title>
            </meta>
        </property>
        """

- ``the following pages exist``: Create pages from the given data, for
  example:

.. code-block:: cucumber

        And the following pages exist:
            | template | url | title | parent | data |
            | article | /articles | Articles | | {"body": "This is article 1"} |
            | article | /articles/foo | Foo | /articles | {"body": "This is article Foo"} |

- ``I am editing page of type :type``: Create a simple page with the given
  type and go to that page:

.. code-block:: cucumber

        Given I am editing a page of type "color_page"

- ``I expect aura component ":type" to appear``: Wait for the aura component
  to appear. Waits for a ``<div/>`` with the ``aura-instance-name`` property
  equal to the given name to appear **and** have more than zero children.

SecurityContext
---------------

**Bundle**: SuluSecurityBundle

- ``Given I am logged in as an administrator``: Login to Sulu using
  ``admin/admin``.

DefaultContext
--------------

**Bundle**: SuluTestBundle

The Default context is the most used context and provides lots of "primitive"
actions.

- ``I click the selector ":selector"``: Click the element identified by the
  given CSS selector (will wait until it appears).
- ``I click on "":selector" in ":container"``: Click the element identified by the
  given CSS selector container within a container CSS selector. (will wait until it appears).
- ``pause``: Pause the test forever -- for debugging.
- ``wait a second``: Wait 1 second. If you use this you are a bad person.
- ``I expect to see ":text"``. Wait until text appears and then assert that it
  did.
- ``I expect to see ":count" ":text" elements``. Wait until text appears and
  then assert that there are a specified number of them.
- ``I fill in the selector :selector with :value``: Set the value on elements
  identified by the given CSS selector.
- ``Press enter on ":selector"``: Simulate an "enter" key being pressed on the
  given CSS selector.
