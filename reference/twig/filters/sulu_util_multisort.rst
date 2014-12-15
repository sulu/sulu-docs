``sulu_util_multisort``
=======================

Allows arrays of arrays or objects to be sorted by any properties which are
accessible via. the Symfony `PropertyAccessor`_ path(s).

.. code-block:: jinja

     {% for content in content.smartcontent|sulu_util_multisort('[title]', 'asc') %}
        {# ... #}
     {% endfor %}

You can specify an array of paths to enable cascading sorting, for example

.. code-block:: jinja

     {% for content in content.smartcontent|sulu_util_multisort(['[title]', '[description]'], 'asc') %}

Arguments:

- **path**: Property path
- **direction**: Direction to sort, either ``ASC`` or ``DESC``

.. _PropertyAccessor: http://symfony.com/doc/current/components/property_access/introduction.html
.. _standard set of Twig functions: http://twig.sensiolabs.org/documentation
