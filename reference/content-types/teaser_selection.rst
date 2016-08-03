Teaser Selection
================

Description
-----------

Shows a orderable list of various items with the possibility to add
different types of items. Each of them returns an object named
`Teaser` which can be used to create a list of different items on
the website.

Properties of the Teaser-Object
-------------------------------

* id
* type (e.g. content or article)
* locale
* title
* description
* moreText (link-text)
* mediaId
* url

In the example of pages you will receive the informations of the
excerpt-tab.

Provider
--------

This content-type is expandable via a provider pattern. A cookbook entry
shows you how to create an own :doc:`/cookbook/teaser-provider`.


Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - present_as
      - collection
      - A collection of strings, which can be configured for different
        presentation modes. If more than one element is given, the user can
        choose between the elements in this collection. The selected value is
        also passed to the HTML template.

Example
-------

*XML Page-Template*

.. code-block:: xml

    <property name="teasers" type="teaser_selection"/>

*Twig-Template*

.. code-block:: html

    <ul property="teasers" class="{{ view.teasers.presentAs|default('') }}">
        {% for teaser in content.teasers %}
            <li><a href="{{ sulu_content_path(teaser.url) }}">{{ teaser.title }}</a></li>
        {% endfor %}
    </ul>
