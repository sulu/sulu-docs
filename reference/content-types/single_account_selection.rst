Single Account selection
========================

Description
-----------

Let you assign one account from the account section to the page.

Parameters
----------

No parameters available.

Return value
------------

See the AccountInterface_ for available variables and functions.

Example
-------

.. code-block:: xml

    <property name="account" type="single_account_selection">
        <meta>
            <title lang="en">Account</title>
        </meta>
    </property>

.. code-block:: twig

    {% set account = content.account %}
    {{ account.name }}

    {% if account.logo %}
        {% set image = sulu_resolve_media(account.logo, app.request.locale) %}

        <img src="{{ image.thumbnails['80x80'] }}" alt="{{ account.name }}">
    {% endif

.. _AccountInterface: https://github.com/sulu/sulu/blob/master/src/Sulu/Bundle/ContactBundle/Entity/AccountInterface.php
