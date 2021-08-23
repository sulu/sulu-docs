Link
======

Description
-----------

The link content type is a universal link provider. It allows to either create urls directly from selected
entities or to insert directly an external url. The configuration allows to enable ``anchor`` and ``target`` fields as
well as the available entity types.

The types can also be extended by adding new types to the ``linkTypeRegistry``.

Parameters
----------

.. list-table::
    :header-rows: 1

    * - Parameter
      - Type
      - Description
    * - enable_target
      - bool
      - Enables the ``target`` input field in the overlay. Default: ``false``
    * - enable_anchor
      - bool
      - Enables the ``anchor`` input field in the overlay. Default: ``false``
    * - types
      - collection
      - List of available types in the dropdown.
        Defaults are: ``page``, ``media``, ``external``.

Example
-------

.. code-block:: xml

    <property name="link" type="link">
        <meta>
            <title lang="en">Link</title>
            <title lang="de">Link</title>
        </meta>
        <params>
            <param name="enable_target" value="true"/>
            <param name="enable_anchor" value="true"/>
            <param name="types" type="collection">
                <param name="page"/>
                <param name="external"/>
                <param name="media"/>
            </param>
        </params>
    </property>

Twig
----

.. code-block:: twig

    <a href="{{ content.link }}" target="{{ view.link.target }}">Click me</a>

Register a new type in javascript
---------------------------------

.. code-block:: javascript

    import linkTypeRegistry from 'sulu-admin-bundle/containers/Link/registries/linkTypeRegistry';

    linkTypeRegistry.add('custom_resource_key', CustomLinkTypeOverlay, translate('app.custom_translation_key'));