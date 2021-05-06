Single Media selection
======================

Description
-----------

Shows a list with the possibility to assign a single asset from the media section
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

Return value
------------

See the Media_ object for available variables and functions.

Example
-------

.. code-block:: xml

    <property name="document" type="single_media_selection">
        <meta>
            <title lang="en">Document</title>
        </meta>
    </property>

Extended Example
----------------

.. code-block:: xml

    <property name="image" type="single_media_selection">
        <meta>
            <title lang="en">Image</title>
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
        </params>
    </property>

Twig
----

.. code-block:: twig

    {% set image = content.image %}
    <img src="{{ image.thumbnails['400x400'] }}" alt="{{ image.title }}" title="{{ image.description|default(image.title) }}">

If your property defines ``displayOptions``, you can access the selected ``displayOption``
via ``view.<property_name>.displayOptions``:

.. code-block:: twig

    {% set image = content.image %}

    <div class="position-{{ view.image.displayOption }}">
        <img src="{{ image.thumbnails['400x400'] }}" alt="{{ image.title }}" title="{{ image.description|default(image.title) }}">
    </div>

If you want to provide a link for downloading a document, you can use ``.url`` attribute
or wrap it with the :doc:`sulu_get_media_url <../twig-extensions/functions/sulu_get_media_url>`
to control which `disposition header`_ the target url should use:

.. code-block:: twig

    <a href="{{ sulu_get_media_url(document.url, 'inline') }}>
        {{ document.title }}
    </a>

.. note::

    For performance reasons you should never use the ``.url`` attribute to render ``images`` on your
    website. Always use ``thumbnails`` and :doc:`configure your image formats <../../../book/image-formats>`
    to provide fast optimized cacheable images.

.. _Media: https://github.com/sulu/sulu/blob/2.x/src/Sulu/Bundle/MediaBundle/Api/Media.php
.. _`disposition header`: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Disposition
