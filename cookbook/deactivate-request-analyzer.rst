How to deactivate the RequestAnalyzer?
======================================

The ``RequestAnalyzer`` has the very important task of recognizing e.g. at
which webspace and locale the current request is targeted. It also recognizes
if the current request is not valid based on some rules, e.g. if there is no 
webspace available at the requested URL. In this case the ``RequestAnalyzer``
throws an exception, which makes it quite easy to find about errors in your
webspace configuration.

However, this behavior might be disturbing for requests in which you are fully
aware that there is no webspace available and you also do not need one. For
these special requests the ``RequestAnalyzer`` can be easily turned off.

This is achieved using the `request attributes from Symfony`_. Sulu scans this
property for field called ``_requestAnalyzer``, and avoids calling it when this
attribute is set to false. The easiest way to achieve this is using the
routing configuration file, which might look something like this:

.. code-block:: yaml

    sulu_example.route:
        path: /some-url
        defaults:
            _controller: SuluExampleBundle:Controller:index
            _requestAnalyzer: false

.. _request attributes from Symfony: http://symfony.com/doc/current/components/http_foundation/introduction.html#component-foundation-attributes

