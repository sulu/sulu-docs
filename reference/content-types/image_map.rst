ImageMap
========

Description
-----------

The image map content type allows to assign an arbitrary amount of hotspots to
an image. These hotspots can be either circles, rectangles or points. It's
possible to define multiple types with different content types included. Every
hotspot needs to be one of the defined types.

A quite common use case is to create a type containing a text editor. Then it's
possible to describe different parts of an image using text. In the front-end
this could be implemented using hover areas. If the user hovers over a hotspot,
the corresponding text could be shown in a popover.

Parameters
----------

No parameters available

Example
-------

XML
^^^

.. code-block:: xml

    <property name="image_map" type="image_map" default-type="basic">
        <meta>
            <title lang="en">Image-Map</title>
            <title lang="de">Image-Map</title>
        </meta>

        <types>
            <type name="basic">
                <meta>
                    <title lang="en">Basic</title>
                    <title lang="de">Grundlegend</title>
                </meta>
                <properties>
                    <property name="title" type="text_line">
                        <meta>
                            <title lang="en">Title</title>
                            <title lang="de">Titel</title>
                        </meta>
                    </property>
                    <property name="description" type="text_area">
                        <meta>
                            <title lang="en">Description</title>
                            <title lang="de">Beschreibung</title>
                        </meta>
                    </property>
                </properties>
            </type>

            <type name="advanced">
                <meta>
                    <title lang="en">Advanced</title>
                    <title lang="de">Erweitert</title>
                </meta>
                <properties>
                    <property name="text" type="text_editor">
                        <meta>
                            <title lang="en">Text</title>
                            <title lang="de">Text</title>
                        </meta>
                    </property>
                </properties>
            </type>
        </types>
    </property>

Twig
^^^^

.. code-block:: twig

    <style>
        .imageMap__container {
            display: inline-block;
            max-width: 800px;
            position: relative;
        }
        .imageMap__img {
            display: block;
            max-width: 100%;
            width: auto;
        }
        .imageMap__hotspot {
            position: absolute;
        }
        .imageMap__hotspot--rectangle {
            border: 2px solid white;
            background-color: rgba(0,0,0,.5);
        }
        .imageMap__hotspot--circle {
            border: 2px solid white;
            border-radius: 100%;
            background-color: rgba(0,0,0,.3);
            transform: translate(-50%, -50%);
        }
        .imageMap__hotspot--point {
            background-color: white;
            border-radius: 100%;
            width: 30px;
            height: 30px;
            transform: translate(-50%, -50%);
        }
        .imageMap__hotspot-text {
            position: absolute;
            display: block;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-weight: bold;
            font-family: sans-serif;
        }
        .imageMap__hotspot--point > .imageMap__hotspot-text {
            color: black;
        }
    </style>

    <div class="imageMap__container">
        <img class="imageMap__img" src="{{ image_map.image.url }}"/>

        {% for index, hotspot in image_map.hotspots %}
            {% set left = hotspot.hotspot.left * 100 %}
            {% set top = hotspot.hotspot.top * 100 %}

            {% if hotspot.hotspot.type == 'rectangle' %}
                {% set width = hotspot.hotspot.width * 100 %}
                {% set height = hotspot.hotspot.height * 100 %}

                <div class="imageMap__hotspot imageMap__hotspot--rectangle"
                    style="left: {{ left }}%; top: {{ top }}%; width: {{ width }}%; height: {{ height }}%;">
                    <span class="imageMap__hotspot-text">{{ index + 1 }}</span>
                </div>
            {% elseif hotspot.hotspot.type == 'circle' %}
                {% set diameter = hotspot.hotspot.radius * 100 * 2 %}

                <div class="imageMap__hotspot imageMap__hotspot--circle"
                    style="left: {{ left }}%; top: {{ top }}%; width: {{ diameter }}%; padding-top: {{ diameter }}%;">
                    <span class="imageMap__hotspot-text">{{ index + 1 }}</span>
                </div>
            {% elseif hotspot.hotspot.type == 'point' %}
                <div class="imageMap__hotspot imageMap__hotspot--point"
                    style="left: {{ left }}%; top: {{ top }}%;">
                    <span class="imageMap__hotspot-text">{{ index + 1 }}</span>
                </div>
            {% endif %}
        {% endfor %}
    </div>

    {% for index, hotspot in image_map.hotspots %}
        <h2>#{{ index + 1 }}</h2>

        {% if hotspot.type == 'basic' %}
            <b>{{ hotspot.title|default }}</b>
            <p>{{ hotspot.description|default }}</p>
        {% elseif hotspot.type == 'advanced' %}
            <p>{{ hotspot.text|default|raw }}</p>
        {% endif %}
    {% endfor %}
