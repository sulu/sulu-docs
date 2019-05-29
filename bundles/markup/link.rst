sulu-link
=========

The link tag provides the possibility to link to other pages by uuid. This uuid
will be validated at response time and replaced by a proper anchor tag.

Example
-------

.. code-block:: html

    <sulu-link href="123-123-123" title="test-title" />
    <sulu-link href="123-123-123" target="_blank">Link Text</sulu-link>

**Results into:**

.. code-block:: html

    <a href="/test" title="test-title">Page Title</a>
    <a href="/test" title="Page Title" target="_blank">Link Text</a>
