Contact selection
=================

Description
-----------

Shows a list with the possibility to assign some people or organizations from
the contact section to a page. Also allows to define a position, which can be
handled later in the template.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - contact
      - boolean
      - Person tab should be visible or not.
    * - account
      - boolean
      - Organizations tab should be visible or not.

Example
-------

.. code-block:: xml

    <property name="contacts" type="contact">
        <meta>
            <title lang="en">Contacts</title>
        </meta>

        <params>
            <param name="contact" value="true"/>
            <param name="account" value="true"/>
        </params>
    </property>

.. code-block:: twig

    <ul property="contacts">
        {% for contact in content.contacts %}
            <li>
                {{ contact.type == 'contact' ? contact.fullName : contact.name }}
                (
                {% for email in contact.emails %}
                    <a href="mailto:{{ email.email }}">{{ email.email }}</a>
                    {% if not loop.last %}&nbsp;|&nbsp;{% endif %}
                {% endfor %}
                )
            </li>
        {% endfor %}
    </ul>
