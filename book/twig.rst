Rendering Pages with Twig
=========================

Twig is an awesome option for rendering HTML. It got some nice features like
blocks and inheritance. That's why we use and love Twig.

Which Twig-Template is used?
----------------------------

In :doc:`templates` we learned how to define a template.

.. code-block:: xml

    <?xml version="1.0" ?>
    <template xmlns="http://schemas.sulu.io/template/template"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:schemaLocation="http://schemas.sulu.io/template/template http://schemas.sulu.io/template/template-1.0.xsd">

        <key>default</key>

        <view>pages/default</view>
        <controller>Sulu\Content\UserInterface\Controller\Website\ContentController::indexAction</controller>
        ...
    </template>


In the page template the view could be set. Internally Sulu appends the format
of the request to find the correct template to render the response. As an
example sulu uses for a html request the template
`templates/pages/default.html.twig` or
`templates/pages/default.xml.twig` for a xml request. With this
feature you are able to define different output format for a single page.

Rendering the Content
---------------------

The ``ContentController`` passes the variables below to Twig. If you need more,
extend it as described in :doc:`../cookbook/custom-controller`.

Content
^^^^^^^

In the content everything you defined in your template is saved.
If you got a title you could easily obtain it from the content-var.

.. code-block:: html

    <h1>{{ content.title }}</h1>

Extension
^^^^^^^^^

In the extension var Sulu writes content from Sulu extensions. Typically stuff
that is defined in separate tabs in the Sulu content section.
At the moment there is the SEO and the excerpt extension, that could be used.
This extensions are available on every page no matter which template you chose.

Here is an example how it could look like in the backend. Notice the
"Excerpt & Categories" tab next to the SEO tab.

.. figure:: ../img/admin-extension-seo.png
    :align: center

You could include the SEO meta tags like this:

.. code-block:: html

    {% include "@SuluWebsite/Extension/seo.html.twig" with {
        "seo": extension.seo|default([]),
        "content": content|default([]),
        "localizations": localizations|default([]),
        "shadowBaseLocale": shadowBaseLocale|default(),
        "defaultLocale": app.request.locale
    } %}

The excerpt data is available from:

.. code-block:: html

    {{ extension.excerpt.title }}
    {{ extension.excerpt.description }}
    {{ extension.excerpt.more }}
    {{ extension.excerpt.icon.thumbnails['50x50'] }}
    {{ extension.excerpt.image.thumbnails['300x300'] }}

View
^^^^

In the ``view`` variable Sulu writes the view data of the defined properties
in your template. As an example the ``media_selection`` stores the
``displayOption`` there.

.. code-block:: html

    {{ view.media.displayOption }}

Other Variables
^^^^^^^^^^^^^^^

 - ``resource``: The entity (e.g. the page) the content belongs to
 - ``template``: The template key of the current page
 - ``availableLocales``: The locales in which the page has content
 - ``mainWebspace``: The key of the main webspace (articles only)
 - ``author``: The contact ID of the author, resolve it with
   :doc:`sulu_resolve_contact <../reference/twig-extensions/functions/sulu_resolve_contact>`
 - ``authored``: The date the page was authored
 - ``lastModified``: The date of the latest change of the page
 - ``shadowBaseLocale``: The locale the page is a shadow of, ``null`` if it is not a shadow page
 - ``localizations``: The URL of the page in every locale of the webspace, see :ref:`twig-localizations`

Sulu 2 also passed ``request.*``, ``uuid``, ``creator``, ``changer``, ``created``, ``changed``,
``published``, ``urls`` and ``segments``. They are not passed anymore. Use Symfony's ``app.request``
to access the request.

To switch the segment of a webspace with segments, link to the route ``sulu_website.segment_switch`` with the
``segment`` key and the ``url`` to redirect to. The path of the route is ``/_sulu_segment_switch``, change it with the
``sulu_website.segments.switch_url`` configuration.

.. code-block:: twig

    <a href="{{ path('sulu_website.segment_switch', {segment: 'w', url: app.request.requestUri}) }}">Winter</a>

.. tip::

    You can also use ``{{ dump() }}`` in the template to see all available variables if you are in dev mode.

.. _twig-localizations:

Localizations
"""""""""""""

``localizations`` is an array indexed by locale. Every entry contains:

 - ``url``: The URL of the page in this locale
 - ``locale``: The locale
 - ``alternate``: ``true`` if the page has its own URL in this locale, ``false`` if ``url`` links
   to the start page of the webspace in this locale

.. code-block:: twig

    {% for localization in localizations %}
        {% if localization.alternate %}
            <link rel="alternate" hreflang="{{ localization.locale }}" href="{{ localization.url }}"/>
        {% endif %}
    {% endfor %}

.. note::

    Available since Sulu 3.1. To change the URLs of a resource, register an implementation of
    ``Sulu\Content\Application\ContentLocalizationsResolver\ContentLocalizationsResolverInterface``
    as a service tagged ``sulu_content.content_localizations_resolver`` with the ``resource_key`` of the resource.
    Content without one uses the default described above.

Navigation
^^^^^^^^^^

There is a Twig function that obtains the menu. You need to pass the key of the
navigation context you defined in your webspace (:doc:`webspaces`).
While editing a page the navigation context could be defined in
*settings > Navigation context*. For many projects one or two navigation
contexts might be enough:

* The main navigation usually is the main entry point for the user of the
  website.
* A footer navigation can be useful for imprints and similar pages.

The following screenshot shows the `Sulu homepage`_ with the main navigation on
the top. As you can see the navigation returned for the navigation contexts are
not necessarily flat, but can also contain sub pages.

.. figure:: ../img/website-navigation-contexts.png
    :align: center

The navigation contexts can also be used in any other combination you want. The
separation into main and footer navigation is only a quite common example.

The advantage of this method is that the content manager can decide on his own
which pages to show in the navigation. This code show an example for creating a
nested navigation using all the pages marked to be shown in the main navigation
context.

.. code-block:: html

    <ul>
        {% for item in sulu_navigation_root_tree('main', 2) %}
        <li>
            <a href="{{ sulu_content_path(item.url) }}"
                title="{{ item.title }}">{{ item.title }}</a>
            {% if item.children|length > 0 %}
                <ul>
                {% for child in item.children %}
                    <li><a href="{{ sulu_content_path(child.url) }}"
                            title="{{ child.title }}">
                        {{ child.title }}
                    </a></li>
                {% endfor %}
                </ul>
            {% endif %}
        </li>
        {% endfor %}
    </ul>

Images
^^^^^^

If there are images defined in your template you could render them by using
this code:

.. code-block:: html

    {% for image in content.images %}
    <div>
        <img src="{{ image.thumbnails['200x100'] }}" alt="{{ image.title }}"/>
        <p>{{ image.title }}</p>
    </div>
    {% endfor %}

Image formats need to be defined in the `image_formats.xml`_ in your config.

Blocks
^^^^^^

The block content type is one of the most important content types in Sulu. It allows content managers to add
different sections and components to a page,
which they can place in any order they want.

In Twig it is recommended to use the following snippet to render the blocks. This way you have
access to the ``content`` and ``view`` variables of each block again.

.. code-block:: twig

    {% for block in content.blocks %}
        {% include 'includes/blocks/' ~ block.type ~ '.html.twig' with {
            content: block,
            view: view.blocks[loop.index0],
        } %}
    {% endfor %}

CSS / JS
--------

You can organize and build your website assets the way you are most familiar with.
If you want to use Symfony's Webpack Encore, have a look at the :doc:`../cookbook/webpack-encore`.

.. _image_formats.xml: https://github.com/sulu/skeleton/blob/2.6/config/image-formats.xml
.. _Sulu Homepage: http://sulu.io
