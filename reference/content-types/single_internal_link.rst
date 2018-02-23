Single internal link
====================

Description
-----------

Shows a field, on which exactly one link to another page can be assigned.

Parameters
----------

No parameters available
 
Example
-------

.. code-block:: xml

    <property name="link" type="single_internal_link">
        <meta>
            <title lang="en">Link</title>
        </meta>
    </property>

Usage
-----

.. code-block:: html

    <a href="{{ sulu_content_path(content.link.url) }}">
        {{ content.link.title }}
    </a>
