Location
========

Description
-----------

Adds the possibility to assign geographic information to a page. Can be used 
either with Google Maps and Open Street Maps.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - countries
      - collection
      - A collection of countries represented as string assigned to unique
        keys (usually the ISO code of the country)
    * - mapProviders
      - collection
      - Defines the available map providers
    * - defaultProvider
      - string
      - The preselected provider in the dropdown
    * - geolocatorName
      - string
      - The alias of the service, which should be used for geolocation

Example
-------

.. code-block:: xml

    <property name="location" type="location">
        <meta>
            <title lang="en">Location</title>
        </meta>
    </property>
