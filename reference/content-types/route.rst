Route
=====

Description
-----------

The ``route`` content type allows to generate urls for **custom entities**.
Have a look at :doc:`/bundles/route/index` to see how to implement routing for your custom entity.

.. note::

    The ``route`` content type should not be used on page templates. For pages, use the :doc:`resource_locator`
    content type instead.

Example
-------

.. code-block:: xml

    <property name="title" type="text_line">
        <tag name="sulu.rlp.part"/>
    </property>

    <property name="subtitle" type="text_line">
        <tag name="sulu.rlp.part"/>
    </property>

    <property name="routePath" type="route">
        <meta>
            <title lang="en">Resource locator</title>
        </meta>
    </property>

Twig
----

You need to use the :doc:`../twig-extensions/functions/sulu_content_path` twig extension
to render the full url.

.. code-block:: twig

    {{ sulu_content_path(content.routePath) }}
