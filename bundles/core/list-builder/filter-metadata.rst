Filter Metadata
===============

The filter metadata extends the basic metadata to add information about filter
form. Each property can have a input-type and parameters which will be use to
customize the input in the filter edit/add form.

Example
-------

.. code-block:: xml

    <identity-property name="titleId" filter-type="auto-complete">
        <orm:field-name>title</orm:field-name>
        <orm:entity-name>%sulu.model.contact.class%</orm:entity-name>

        <filter-type-parameters>
            <filter:parameter key="prefetchUrl">
                <![CDATA[http://sulu.lo/admin/api/contact/titles]]>
            </filter:parameter>
            <filter:parameter key="resultKey">contactTitles</filter:parameter>
            <filter:parameter key="valueKey">title</filter:parameter>
        </filter-type-parameters>
    </identity-property>

This Example creates a input where the user can select a title from the given
api endpoint. The selected title will be saved as an id.

Filter type
-----------

.. list-table::

    * - **name**
      - **description**
    * - **string**
      - Simple string value
    * - **number**
      - Simple number (int, double, ...) value
    * - **date/datetime**
      - Datetime value
    * - **boolean**
      - Simple boolean value
    * - **tags**
      - Sulu-Tags
    * - **auto-complete**
      - Could be any value which has an API End-point with search support

Parameters
----------

Currently only the "auto-complete" input-type can be customized via parameters.

.. list-table::

    * - **parameter**
      - **description**
    * - **prefetchUrl**
      - Url which will be used to load all possible values
    * - **remoteUrl**
      - This url will be used to search for filled in value
    * - **resultKey**
      - Key which contains result in the ``_embedded``
    * - **valueKey**
      - Name of the property which contains the value of the item
    * - **searchParameter** [optional - default "search"]
      - Parameter name which will be appended to the given remote-url

The given example before shows how to use this parameters.
