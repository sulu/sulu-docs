Single Icon selection
======================

Description
-----------

Shows a list with the possibility to assign a single icon from a configured iconfont or from provided svgs.

.. warning::

    This content type is only available for Sulu >= 2.6.9.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - icon_set
      - string
      - Defines which icon set should be used. This parameter is required and needs to be configured.

Return value
------------

Returns the name of the selected icon or svg.

Configuration
----------------

By default, you can select Sulu icons in the single icon selection field. If you’d like to add your own icon font or SVGs, simply extend the configuration as shown below.

.. code-block:: yaml

    sulu_admin:
        icon_sets:
            iconfont: icomoon://%kernel.project_dir%/public/iconfont/selection.json # Path to the icomoon selection.json
            svgs: svg://%kernel.project_dir%/public/svgs # Path to the svgs folder

Example
-------

.. code-block:: xml

    <property name="icon" type="single_icon_selection">
        <meta>
            <title>Icon</title>
        </meta>

        <params>
<!-- "sulu" is the default configured icon font for the Sulu icons-->
<!--        <param name="icon_set" value="sulu"/>-->
            <param name="icon_set" value="iconfont"/>
        </params>
    </property>

Twig
----

.. code-block:: twig

    {% set icons = content.icon %}
    <span class="icon-{{ icon.icon }}"></span>

    {% set svg = content.svg %}
    <img src="{{ asset('svgs/' ~ svg.icon ~ '.svg') }}"/>

