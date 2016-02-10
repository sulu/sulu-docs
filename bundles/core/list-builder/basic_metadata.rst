Basic Metadata
==============

List-Builder metadata describes the reset-resource. This resource consists of
properties with different types of metadata.

The standard metadata of a property could be:

.. list-table::

    * - general
      - description of field which will be used to display the datagrid in
        the Sulu-Admin.
    * - doctrine
      - describes the doctrine-path to find the in the database.

Example
-------

.. code-block:: xml

    <class xmlns="http://schemas.sulu.io/class/general"
       xmlns:orm="http://schemas.sulu.io/class/doctrine">
        <properties>
            <property name="id" translation="public.id" disabled="true" default="false" type="integer">
                <orm:field-name>id</orm:field-name>
                <orm:entity-name>%sulu.model.contact.class%</orm:entity-name>
            </property>

            <property name="firstName" translation="contact.contacts.firstName" default="true">
                <orm:field-name>firstName</orm:field-name>
                <orm:entity-name>%sulu.model.contact.class%</orm:entity-name>
            </property>

            <property name="lastName" translation="contact.contacts.lastName" default="true">
                <orm:field-name>lastName</orm:field-name>
                <orm:entity-name>%sulu.model.contact.class%</orm:entity-name>
            </property>

            <property name="avatar" translation="public.avatar" default="true" type="thumbnails" sortable="false">
                <orm:field-name>id</orm:field-name>
                <orm:entity-name>SuluMediaBundle:Media</orm:entity-name>

                <orm:joins>
                    <orm:join>
                        <orm:entity-name>SuluMediaBundle:Media</orm:entity-name>
                        <orm:field-name>%sulu.model.contact.class%.avatar</orm:field-name>
                    </orm:join>
                </orm:joins>
            </property>
        </properties>
    </class>

Property Types
--------------

To differentiate property-types there are different xml-tags.

property
~~~~~~~~

A property is a simple doctrine-field.

**Attributes**

.. list-table::

    * - name
      - name of the field
    * - translation [optional]
      - key to translate the name of the property
    * - disabled [optional - default false]
      - If true the user cannot exclude this field from a table
    * - default [optional - default false]
      - If true this field is automatically selected for the columns of a table
    * - type [optional - default string]
      - Defines the type of data - infects the display-format in the table
    * - sortable [optional - default true]
      - If true the field can be sorted ASC or DESC
    * - editable [optional - default false]
      - If true the field can be edited in the table
    * - width [optional]
      - The width of the table column
    * - min-width [optional]
      - The min-width of the table column for responsive design
    * - css-class [optional]
      - Custom css-class for column

**Childnodes**

.. list-table::

    * - orm:field-name
      - Defines the doctrine field-name
    * - orm:entity-name
      - Defines the doctrine entity-name
    * - orm:joins
      - Defines the join-path to get the doctrine-field

In the tags field-name and entity-name it is possible to use
container-parameter ``%parameter%``.

.. note::

    If joins are use multiple times in a single xml-file, it is possible to
    define it directly in the class-tag, add a name-attribute and reference
    it in the property-tag ``<orm:joins ref="address"/>``.

concatenation-property
~~~~~~~~~~~~~~~~~~~~~~

Concatenates multiple-fields.

**Attributes**

All attributes from property with the following additions:

.. list-table::

    * - orm:glue
      - glue between the field-values

**Childnodes**

.. list-table::

    * - orm:field
      - multiple definitions of fields which should be concatenated (can also
        contain joins)

.. note::

    Fields can reference to an existing property-definition over the
    ``property-ref`` attribute (see example below).

.. code-block:: xml

    <concatenation-property name="fullName" orm:glue=" ">
        <orm:field name="firstName">
            <orm:field-name>firstName</orm:field-name>
            <orm:entity-name>%sulu.model.contact.class%</orm:entity-name>
        </orm:field>
        <orm:field property-ref="lastName"/>
    </concatenation-property>
