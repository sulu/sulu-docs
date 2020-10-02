Account selection
========================

Description
-----------

Let you assign multiple account from the account section to the page.

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

Return value
------------

See the Account_ for available variables and functions.

Example
-------

.. code-block:: xml

    <property name="accounts" type="account_selection">
        <meta>
            <title lang="en">Accounts</title>
        </meta>
    </property>

Twig
----

.. code-block:: twig

    {% for account in content.accounts %}
        <h3>{{ account.name }}</h3>
    {% endif %}

.. _Account: https://github.com/sulu/sulu/blob/master/src/Sulu/Bundle/ContactBundle/Api/Account.php
.. _jexl: https://github.com/TomFrost/jexl
