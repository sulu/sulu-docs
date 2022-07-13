sulu-link
=========

The sulu-link tag allows to link to pages and other entities in the application by their id.
This id of the tag will be validated and replaced by a proper anchor tag when a response is generated.
Furthermore, the sulu-link tag allows to add a query string (?) or an hash (#) that is forwarded
to the resulting anchor.

In a basic installation, the tag supports 2 different providers: ``page`` (default) and ``media``.
Additional providers can be implemented by :doc:`registering a service with the sulu.link.provider tag<../../cookbook/link-provider>`.

Example
-------

.. code-block:: html

    <sulu-link href="123-123-123" />
    <sulu-link href="123-123-123" title="test-title" />
    <sulu-link href="123-123-123" target="_blank">Link Text</sulu-link>
    <sulu-link href="123-123-123#test-anchor">Anchor Example</sulu-link>
    <sulu-link href="123-123-123?query=test">Query String Example</sulu-link>
    <sulu-link provider="page" href="123-123-123" target="_blank">Link Text</sulu-link>
    <sulu-link provider="media" href="1" title="test-title"/>
    <sulu-link provider="media" href="1">Link Text</sulu-link>

**Results into:**

.. code-block:: html

    <a href="http://example.com/test" title="Page Title">Page Title</a>
    <a href="http://example.com/test" title="test-title">Page Title</a>
    <a href="http://example.com/test" title="Page Title" target="_blank">Link Text</a>
    <a href="http://example.com/test#test-anchor" title="Page Title">Anchor Example</a>
    <a href="http://example.com/test?query=test" title="Page Title">Query String Example</a>
    <a href="http://example.com/test" title="Page Title" target="_blank">Link Text</a>
    <a href="http://example.com/media/1/download/image.jpg?v=1" title="test-title">Media Title</a>
    <a href="http://example.com/media/1/download/image.jpg?v=1" title="Media Title">Link Text</a>
    
Provider Attributes
----------

There might be situations where you need to know which provider was used even after the tag was converted to an anchor tag. In that case you can use the provider attributes feature to pass the provider information as a data attribute to the rendered anchor tag.

.. code-block:: yaml

    # config/packages/sulu_markup.yaml
    sulu_markup:
        link_tag:
            provider_attribute: 'data-provider'

**Example:**

.. code-block:: html

    <sulu-link provider="page" href="123-123-123">Link Text</sulu-link>
    <sulu-link provider="media" href="1"/>

**Results into:**

.. code-block:: html

    <a href="http://example.com/test" title="Page Title" data-provider="page">Link Text</a>
    <a href="http://example.com/media/1/download/image.jpg?v=1" data-provider="media">Media Title</a>

Now you will be able to target the links in CSS and Javascript by using the data attribute as a selector. This allows things such as:

* Adding an icon to download (media) links
* Format internal links differently
* Add custom analytics

**Example:**

.. code-block:: css

    a[data-provider="media"] {
        font-color: green;
    }

