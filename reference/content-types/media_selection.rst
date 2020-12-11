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
    * - min
      - string
      - The minimum number of selected media
    * - max
      - string
      - The maximum number of selected media

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
        </params>
    </property>

Twig
----

.. code-block:: twig

    {% for image in content.images %}
        <img src="{{ image.thumbnails['400x400'] }}" alt="{{ image.title }}" title="{{ image.description|default(image.title) }}">
    {% endfor %}

If your property defines ``displayOptions`, you can access the selected ``displayOption``
via ``view.<property_name>.displayOptions``:

.. code-block:: twig

    <div class="position-{{ view.images.displayOption }}">
        {% for image in content.images %}
            <img src="{{ image.thumbnails['400x400'] }}" alt="{{ image.title }}" title="{{ image.description|default(image.title) }}">
        {% endfor %}
    </div>

If you want to provide a link for downloading a document, you can use ``.url`` attribute
or wrap it with the <sulu_get_media_url>:doc:`../twig-extensions/functions/sulu_get_media_url`
to control which `disposition header`_ the target url should use:

.. code-block:: twig

    <ul>
        {% for document in content.documents %}
            <li>
                <a href="{{ document.url }}>
                    {{ document.title }}
                </a>
            </li>
        {% endfor %}
    </ul>

.. note::

    For performance reasons you should never use the ``.url`` attribute to render ``images`` on your
    website. Always use ``thumbnails`` and <configure your image formats>:doc:`../../../book/image-formats`
    to provide fast optimized cacheable images.

.. _`disposition header`: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Disposition
