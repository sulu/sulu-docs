Contact selection
========================

Description
-----------

Let you assign one contact from the contact section to the page.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - item_disabled_condition
      - string
      - Allows to set a `jexl`_ expression that evaluates if an item should be displayed as disabled.
        Disabled items cannot be selected.
    * - allow_deselect_for_disabled_items
      - bool
      - Defines if the user should be able to deselect an item that is disabled. Default value is true.
    * - request_parameters
      - collection
      - Collection of parameters that are appended to the requests sent by the selection.
    * - resource_store_properties_to_request
      - collection
      - Collection of property names.
        The value of the respective properties are appended to the requests sent by the selection.
    * - min
      - string
      - The minimum number of selected contacts
    * - max
      - string
      - The maximum number of selected contacts

Return value
------------

See the ContactInterface_ for available variables and functions.

Example
-------

.. code-block:: xml

    <property name="contacts" type="contact_selection">
        <meta>
            <title lang="en">Contacts</title>
        </meta>
    </property>

Twig
----

.. code-block:: twig

    {% for contact in content.contacts %}
        <h3>{{ contact.fullName }}</h3>
    {% endfor %}

.. _ContactInterface: https://github.com/sulu/sulu/blob/master/src/Sulu/Bundle/ContactBundle/Entity/ContactInterface.php
.. _jexl: https://github.com/TomFrost/jexl
