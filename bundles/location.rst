LocationBundle
==============

The SuluLocationBundle provides services related to maps and geolocation.

Configuration
-------------

.. code-block:: yaml

    # Default configuration for extension with alias: "sulu_location"
    sulu_location:
        geolocator:                   ~ # One of "nominatim"; "google"
        geolocators:
            nominatim:
                api_key:              ''
                endpoint:             'http://open.mapquestapi.com/nominatim/v1/search.php'
            google:
                api_key:              ''

Services
--------

Geolocators
^^^^^^^^^^^

The Geolocator services in the map bundle provide a common API to geocoding services, in particular
for obtaining address information from user searches.

Geolocators are PHP services and can be found in the ``Geolocator/Service`` namespace.

Nominatim
"""""""""

.. note::

    `Nominatim`_ (from the Latin, 'by name') is a tool to search OSM data by name and
    address and to generate synthetic addresses of OSM points (reverse geocoding)

There are several available Nominatim providers that can be configured via the ``endpoint`` configuration.
Some of them might require an authentication token that can be configured via the ``api_key`` configuration.
Have a look at the `OpenStreetMap Wiki`_ for an up-to-date list of providers.

**Configuration:**

- ``endpoint``: The endpoint to use (eg. ``http://open.mapquestapi.com/nominatim/v1/search.php`` for the
  Mapquest provider)
- ``api_key``: Authentication key for the configured Nominatim endpoint.
  (Can be created via `Developer portal of Mapquest`_ for the Mapquest provider).

Google Maps
"""""""""""

`Google geocoding`_ API.

**Configuration:**

- ``api_key``: Create the api-key in the `Google Cloud Console`_

.. _Nominatim: http://wiki.openstreetmap.org/wiki/Nominatim
.. _OpenStreetMap Wiki: http://wiki.openstreetmap.org/wiki/Nominatim
.. _Developer portal of Mapquest: https://developer.mapquest.com/user/me/apps
.. _Google geocoding: https://developers.google.com/maps/documentation/geocoding
.. _Google Cloud Console: http://g.co/dev/maps-no-account
