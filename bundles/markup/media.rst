sulu-media
==========

The media tag provides the possibility to create download links for medias.

Example
-------

.. code-block:: html

    <sulu-media id="1" title="Title"/>
    <sulu-media id="1" title="Title">Link-Text</sulu-media>
    <sulu-media id="1">Link-Text</sulu-media>

**Results into:**

.. code-block:: html

    <a href="/media/1/download/image.jpg?v=1" title="Title">Media-Title</a>
    <a href="/media/1/download/image.jpg?v=1" title="Title">Link-Text</a>
    <a href="/media/1/download/image.jpg?v=1" title="Media-Title">Link-Text</a>
