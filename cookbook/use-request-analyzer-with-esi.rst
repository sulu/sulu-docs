How to use the RequestAnalyzer with ESI requests?
-------------------------------------------------

The `symfony documentation`_ already describes how to use `edge side includes`_
to cache parts of pages with different life times. However, if you are using
the ``render_esi`` function in combination with the ``controller`` function as
shown in the following code, you might encounter issues:

.. code-block:: jinja

    {{ render_esi(controller('AppBundle:News:latest', { 'maxPerPage': 5 })) }}

This will probably work for most controllers, but if the ``latestAction`` of
the ``NewsController`` makes use of the ``RequestAnalyzer`` it might fail,
because the ``RequestAnalyzer`` can't analyze the request. This is caused by
the fact that Symfony generates a special URL for this ``render_esi`` call.

The solution to this problem is to also pass the portal and the locale (if the
rendered content should not have the same locale as the rest of the page) to
the options:

.. code-block:: jinja

    {{ render_esi(controller('AppBundle:News:latest', { 'maxPerPage': 5, _portal: request.portalKey, _locale: request.locale })) }}

.. _symfony documentation: https://symfony.com/doc/current/book/http_cache.html#using-edge-side-includes
.. _edge side includes: https://en.wikipedia.org/wiki/Edge_Side_Includes

