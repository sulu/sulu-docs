Text editor
===========

Description
-----------

Shows a rich text editor, capable of formatting text as well. The output of the
editor will be stored as HTML in a string field.

Example
-------

.. code-block:: xml

    <property name="description" type="text_editor">
        <meta>
            <title lang="en">Description</title>
        </meta>
    </property>
    
Usage
-----

When outputting the text editor field in twig the `raw filter`_ need to be used:

.. code-block:: twig

    {{ content.description|raw }}
    
.. _raw filter: https://twig.symfony.com/doc/2.x/filters/raw.html
