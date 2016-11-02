Media selection
===============

Description
-----------

Shows a list with the possibility to assign some assets from the media section
to a page. Also allows to define a position, which can be handled later in the
template.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - types
      - string
      - A comma separated list of available asset types to assign. Each item in
        the list must be one of ``document``, ``image``, ``video`` or ``audio``.
    * - displayOptions
      - collection
      - A collection of booleans, which defines to which positions the assets
        can be assigned (``leftTop``, ``top``, ``rightTop``, ...)
    * - defaultDisplayOption
      - string
      - Defines which of the displayOptions is the default one
    * - formats
      - collection
      - A collection of image formats, which will be available when opening the
        cropping overlay in the page form. Contains all image formats by default.

Example
-------

.. code-block:: xml

    <property name="images" type="media_selection">
        <meta>
            <title lang="en">Images</title>
        </meta>

        <params>
            <param name="types" value="image,video"/>
            <param name="displayOptions" type="collection">
                <param name="leftTop" value="true"/>
                <param name="top" value="true"/>
                <param name="rightTop" value="true"/>
                <param name="left" value="true"/>
                <param name="middle" value="false"/>
                <param name="right" value="true"/>
                <param name="leftBottom" value="true"/>
                <param name="bottom" value="true"/>
                <param name="rightBottom" value="true"/>
            </param>
            <param name="defaultDisplayOption" value="left"/>
            <param name="formats" type="collection">
                <param name="640x960" />
            </param>
        </params>
    </property>
