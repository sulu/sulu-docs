Location
========

Description
-----------

Shows a map to assign geographic coordinates. If you want to search for coordinates using
an address in the administration interface, you need to setup a ``geolocator`` in the
:doc:`/bundles/location` configuration.

Parameters
----------

No parameters available

Example
-------

.. code-block:: xml

    <property name="location" type="location">
        <meta>
            <title lang="en">Location</title>
        </meta>
    </property>

Twig
----

.. code-block:: twig

    {{ content.location.lat }} <br>
    {{ content.location.long }} <br>
    {{ content.location.street }} <br>
    {{ content.location.number }} <br>
    {{ content.location.code }} <br>
    {{ content.location.town }} <br>
    {{ content.location.country }} <br>
    {{ content.location.title }} <br>
    {{ content.location.zoom }}
