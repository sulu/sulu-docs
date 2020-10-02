Single Contact selection
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

Return value
------------

See the ContactInterface_ for available variables and functions.

Example
-------

.. code-block:: xml

    <property name="contact" type="single_contact_selection">
        <meta>
            <title lang="en">Contact</title>
        </meta>
    </property>

Twig
----

You need to use the :doc:`../twig-extensions/functions/sulu_resolve_media` if you want to render
the contact avatar image.

.. code-block:: twig

    {% set contact = content.contact %}
    {{ contact.fullName }}

    {% if contact.avatar %}
        {% set image = sulu_resolve_media(contact.avatar, app.request.locale) %}

        <img src="{{ image.thumbnails['80x80'] }}" alt="{{ contact.fullName }}">
    {% endif %}

.. _ContactInterface: https://github.com/sulu/sulu/blob/master/src/Sulu/Bundle/ContactBundle/Entity/ContactInterface.php
.. _jexl: https://github.com/TomFrost/jexl
