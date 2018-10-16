Single Contact selection
========================

Description
-----------

Let you assign one contact from the contact section to the page.

Parameters
----------

No parameters available.

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

.. code-block:: twig

    {% set contact = content.contact %}
    {{ contact.fullName }}

    {% if contact.avatar %}
        {% set image = sulu_resolve_media(contact.avatar, app.request.locale) %}

        <img src="{{ image.thumbnails['80x80'] }}" alt="{{ contact.fullName }}">
    {% endif

.. _ContactInterface: https://github.com/sulu/sulu/blob/master/src/Sulu/Bundle/ContactBundle/Entity/ContactInterface.php
