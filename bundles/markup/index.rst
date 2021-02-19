MarkupBundle
============

The MarkupBundle provides the feature of extending output formats as with
different so called tags. These tags will be automatically parsed and replaced
before the response will be sent.

Example
-------

This example contains a sulu-related example. The tag ``sulu-link`` represents
a link to another page. This tag will be replaced via a valid anchor where the
`href` attribute contains the UUID of the page.

.. code-block:: html

    <html>
        <body>
            <sulu-link href="123-123-123" title="test-title" />
        </body>
    </html>

**Results into:**

.. code-block:: html

    <html>
        <body>
            <a href="http://example.com/test" title="test-title">Page Title</a>
        </body>
    </html>

Core Tags
---------

.. toctree::
    :maxdepth: 1

    link

Extending
---------

To enable replacement of your custom tags you can define a service which
implements the ``TagInterface``.

.. code-block:: php

    class LinkTag implements TagInterface
    {
        /**
         * Returns new tag with given attributes.
         *
         * @param array $attributes attributes array of each tag occurrence.
         *
         * @return array Tag array to replace all occurrences.
         */
        public function parseAll($attributesByTag) {
            $result = [];
            foreach($attributesByTag as $tag => $attributes) {
                $url = ; // load url via uuid from document-manager
                $pageTitle = ...; // load page-title via uuid from document-manager

                $result[$tag] = sprintf('<a href="%s" title="%s">%s</a>', $url, $pageTitle, $attributes['content']);
            }

            return $result;
        }
    }

When registering your service simple add the tag
``<tag name="sulu_markup.tag" tag="link"/>``.

Namespaces
----------

Namespaces will be used to find tags with a special behavior. The default
namespace is ``sulu``, but you can register your own namespace by adding a new
service and register your ``TagInterface`` implementations with this new
namespace.

.. code-block:: xml

    <service id="app.html_extractor"
             class="Sulu\Bundle\MarkupBundle\Markup\HtmlTagExtractor">
        <argument type="string">custom-namespace</argument>

        <tag name="sulu_markup.parser.html_extractor"/>
    </service>

    <service id="app.tag" class="AppBundle\CustomTag">
        <tag name="sulu_markup.tag" namespace="custom-namespace"
             tag="custom-tag" type="html" />
    </service>

With this definitions you can use ``<custom-namespace-custom-tag/>`` in your
response.
