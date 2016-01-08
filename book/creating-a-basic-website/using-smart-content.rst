Using smart content
===================

What is smart content?
----------------------

The smart content is one of our most powerful content types. It allows the
content editor to dynamically configure an aggregation of content, whereby
content does not only mean pages. This is possible due to the data providers,
which can be registered in the system. A data provider defines which options
are supported, and is responsible for loading the data. Sulu is already shipped
with different data providers, one for pages from the content management
section, and another two for contacts and accounts.

The configuration will be resolved to a set of arrays, which can easily be used
in a twig template.

Configure a smart content
-------------------------

The smart content is configured in the template definition. The template
definition is already described in :doc:`adding-a-template`. All that has to be
done is to add another property for the smart content. This configuration can
look something like the following typical XML fragment:

.. code-block:: xml

    <property name="pages" type="smart_content">
        <meta>
            <title lang="en">Smart Content</title>
        </meta>

        <params>
            <param name="properties" type="collection">
                <param name="title" value="title"/>
                <param name="description" value="excerpt.description"/>
            </param>
            <param name="present_as" type="collection">
                <param name="one">
                    <meta>
                        <title lang="en">One column</title>
                    </meta>
                </param>
                <param name="two">
                    <meta>
                        <title lang="en">Two column</title>
                    </meta>
                </param>
            </param>
        </params>
    </property>

In this XML fragment a ``smart_content`` property named ``pages`` is defined.
For pages there is also the possibility to define a ``properties`` parameter,
as you can see in the previous fragment. In this collection property can be
defined which properties of the page should be returned in the array passed to
the twig template. The ``name`` of the parameter describes how the property
will be accessible in the twig template, and the value is the name of the
property on the page. Additionally there is the excerpt extension, which can be
used as well, there has just ``excerpt.`` to be prefixed. This extension is
available for all pages, so it is a safe bet. The problem with other properties
is that you have to make sure or at least check in the twig template if the
property exists.

The value of the ``present_as`` property is injected into a dropdown, where the
content editor can choose between different styles, which of course have to be
implemented by the creator of the twig template. Popular options here are one
or two columns with variations like with or without images.

There are also more parameters to tweak the smart content, for a deeper
understanding of this there is the reference documentation of the
:doc:`../../reference/content-types/smart_content`.

Use the smart content in a twig template
----------------------------------------

Using the pages that are returned from the smart content in a twig template is
very easy. As already described the data is returned as an array in the twig
template in the ``content`` variable. In the ``view`` variable the
configuration data of the smart content is stored.

This way it is really simple to display this information using a twig template:

.. code-block:: jinja

    <div property="pages">
    {% for page in content.pages %}
        <div class="{{ view.pages.present_as }}">
            <h2><a href="{{ sulu_content_path(page.url) }}">{{ page.title }}</a></h2>
            <p>{{ page.description }}</p>
        </div>
    {% endfor %}
    </div>

The ``pages`` in ``content.pages`` refers to the name of the property in the
template definition. Every page being returned by the filter described in the
smart content has its own array in this variable, so that we can iterate over
it. In the ``view`` variable the configuration of the smart content is
accessible, which can be used e.g. as a CSS class in this example. This way the
one or two column layout can be created by using CSS.

The ``page`` loop variable can then be used to access the actual content from
the page. A Sulu twig extension provides the ``sulu_content_path`` method,
which builds the final URL with all the additional information required. 

For more and deeper information about twig there is the excellent `twig
documentation`_.

The next step is how to add localization to Sulu.

.. _`twig documentation`: http://twig.sensiolabs.org/documentation

Pagination
----------

The smart content supports pagination, which can be activated with the param
``max_per_page`` described in the content-type reference
:doc:`../../reference/content-types/smart_content`.

.. code-block:: jinja

    <ul class="pagination">
        {% set page = view.pages.page %}

        {% if page-1 >= 1 %}
            <li><a href="{{ sulu_content_path(content.url) }}?p={{ page-1 }}">&laquo;</a></li>
        {% endif %}
        {% if view.smartcontent.hasNextPage %}
            <li><a href="{{ sulu_content_path(content.url) }}?p={{ page+1 }}">&raquo;</a></li>
        {% endif %}
    </ul>

    <div property="pages">
    {% for page in content.pages %}
        <div class="{{ view.pages.present_as }}">
            <h2><a href="{{ sulu_content_path(page.url) }}">{{ page.title }}</a></h2>
            <p>{{ page.description }}</p>
        </div>
    {% endfor %}
    </div>

The view variable ``page`` contains the current page number (default: 1) and
``hasNextPage`` is a flag which is true if another page exists.

.. warning::
    To avoid performance issues it is not possible to get a number of maximum
    page because the system would have to load the whole content of each page
    to determine how many pages would fit to the filters.

If you want to use different parameters for different smart content on the same
page you can define the GET-parameter with the property param
``page_parameter``.
