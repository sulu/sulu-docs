MarkupBundle
============

The MarkupBundle provides the feature of declaring different "relations" as
html-tag. This tags will be automatically parsed before the response will be
send.

Example
-------

This example contains a sulu-related example. The tag ``sulu:link`` represents
a link to another page. This tag will be replaces via a valid anchor where the
href links to the page with given UUID.

.. code-block:: html

    <html>
        <body>
            <sulu:link href="123-123-123" title="test-title" />
        </body>
    </html>

**Results into:**

.. code-block:: html

    <html>
        <body>
            <a href="/test" title="test-title">Page Title</a>
        </body>
    </html>

Extending
---------

To enable replacement of your "custom" tags you can define a service which
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

                $result[$tag] = sprintf('<a href="%s" title="%s">%s</a>', $url, $$pageTitle, $attributes['content']);
            }

            return $result;
        }
    }

When registering your service simple add the tag
``<tag name="sulu_markup.tag" tag="link"/>``.
