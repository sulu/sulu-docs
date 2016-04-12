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

Currently this content type only returns the UUID of the target page. In
order to construct a link to the page use:

.. code-block:: html

    {% set target = sulu_content_load(content.myLink) %}

Then ``target.content`` will give you access to the URL and other properties
of the target page.
