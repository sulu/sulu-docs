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
            <property name="id" translation="public.id" type="integer">
                <orm:field-name>id</orm:field-name>
                <orm:entity-name>%sulu.model.contact.class%</orm:entity-name>
            </property>

            <property name="firstName" translation="contact.contacts.firstName" display="always">
                <orm:field-name>firstName</orm:field-name>
                <orm:entity-name>%sulu.model.contact.class%</orm:entity-name>
            </property>

            <property name="lastName" translation="contact.contacts.lastName" display="always">
                <orm:field-name>lastName</orm:field-name>
                <orm:entity-name>%sulu.model.contact.class%</orm:entity-name>
            </property>

            <property name="avatar" translation="public.avatar" type="thumbnails" sortable="false">
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

    * - **name**
      - **name of the field**
    * - **translation** [optional]
      - key to translate the name of the property
    * - **display** [optional - default no]
      - Defines the visibility in the datagrid
    * - **type** [optional - default string]
      - Defines the type of data - infects the display-format in the table
    * - **sortable** [optional - default true]
      - If true the field can be sorted ASC or DESC
    * - **editable** [optional - default false]
      - If true the field can be edited in the table
    * - **width** [optional]
      - The width of the table column
    * - **min-width** [optional]
      - The min-width of the table column for responsive design
    * - **css-class** [optional]
      - Custom css-class for column

For the display there are for possible values:

.. list-table::

    * - **option**
      - **displayed by default**
      - **can be en/disabled**
    * - **always**
      - yes
      - no
    * - **never**
      - no
      - no
    * - **yes**
      - yes
      - yes
    * - **no**
      - no
      - yes

**Childnodes**

.. list-table::

    * - **orm:field-name**
      - Defines the doctrine field-name
    * - **orm:entity-name**
      - Defines the doctrine entity-name
    * - **orm:joins**
      - Defines the join-path to get the doctrine-field

In the tags field-name and entity-name it is possible to use
container-parameter ``%parameter%``.

.. note::

    If joins are use multiple times in a single xml-file, it is possible to
    define it directly in the class-tag, add a name-attribute and reference
    it in the property-tag ``<orm:joins ref="address"/>``.

concatenation-property
~~~~~~~~~~~~~~~~~~~~~~

Concatenates multiple doctrine fields.

**Attributes**

All attributes from property with the following additions:

.. list-table::

    * - **orm:glue**
      - glue between the field-values

**Childnodes**

.. list-table::

    * - **orm:field**
      - multiple definitions of fields which should be concatenated (can also
        contain joins)

.. note::

    Fields can reference to an existing property-definition over the
    ``property-ref`` attribute (see example below).

**Example**

.. code-block:: xml

    <concatenation-property name="fullName" orm:glue=" ">
        <orm:field name="firstName">
            <orm:field-name>firstName</orm:field-name>
            <orm:entity-name>%sulu.model.contact.class%</orm:entity-name>
        </orm:field>
        <orm:field property-ref="lastName"/>
    </concatenation-property>


group-concat-property
~~~~~~~~~~~~~~~~~~~~~

Concatenates a doctrine field (one-to-many or many-to-many).

**Attributes**

All attributes from property with the following additions:

.. list-table::

    * - **orm:glue**
      - glue between the field-values

**Childnodes**

All childnodes from property without any addition.

**Example**

.. code-block:: xml

    <group-concat-property name="tagIds" translation="public.tags" display="never" orm:glue=",">
        <orm:field-name>id</orm:field-name>
        <orm:entity-name>SuluTagBundle:Tag</orm:entity-name>

        <orm:joins>
            <orm:join>
                <orm:entity-name>SuluTagBundle:Tag</orm:entity-name>
                <orm:field-name>%sulu.model.contact.class%.tags</orm:field-name>
            </orm:join>
        </orm:joins>
    </group-concat-property>

identity-property
~~~~~~~~~~~~~~~~~

Returns the foreign-key of given doctrine-field. This can be used for filtering
purposes.

**Attributes**

All attributes from property without any addition.

**Childnodes**

All childnodes from property without any addition.

**Example**

.. code-block:: xml

    <identity-property name="titleId" translation="public.title" display="never">
        <orm:field-name>title</orm:field-name>
        <orm:entity-name>%sulu.model.contact.class%</orm:entity-name>
    </identity-property>
