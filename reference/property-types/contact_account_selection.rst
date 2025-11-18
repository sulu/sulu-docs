Contact account selection
=========================

Description
-----------

Shows a list with the possibility to assign some people or organizations from
the contact section to a page. Also allows to define a position, which can be
handled later in the template.

Parameters
----------

No parameters available

Example
-------

.. code-block:: xml

    <property name="contacts" type="contact_account_selection">
        <meta>
            <title lang="en">Contacts / Accounts</title>
        </meta>
    </property>

Twig
----

.. code-block:: twig

    <ul>
        {% for contact in content.contacts %}
            <li>
                {{ contact.fullName|default(contact.name) }}
                (
                {% for email in contact.contactDetails.emails %}
                    <a href="mailto:{{ email.email }}">{{ email.email }}</a>
                    {% if not loop.last %}&nbsp;|&nbsp;{% endif %}
                {% endfor %}
                )
            </li>
        {% endfor %}
    </ul>
