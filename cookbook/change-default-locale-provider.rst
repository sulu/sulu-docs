How to change the default locale provider?
==========================================

Perhaps first we should explain the purpose of a ``DefaultLocaleProvider``. A ``DefaultLocaleProvider`` is used
to determine the locale of a request if the request does not already contain the information. E.g. if you open
http://sulu.io Sulu doesn't know which localization should be displayed, because an english and a german version
of the homepage is available. In this case the ``DefaultLocaleProvider`` is requested to provide a default locale
which is used to redirect to http://sulu.io/en.

Currently two providers are available. One makes use of the portal default localization configuration. The other
tries to find the best matching locale based on the preferred language of the HTTP request.

You can provide an own ``DefaultLocaleProvider`` which has to implement the ``DefaultLocaleProviderInterface``.

Available default locale providers:

+---------------------------------------------------+-------------------------------------------------------+
| Service ID                                        | Description                                           |
+---------------------------------------------------+-------------------------------------------------------+
| `sulu_website.default_locale.portal_provider`     | Use portal default localization configuration         |
| `sulu_website.default_locale.request_provider`    | Use preferred language of the HTTP request            |
+---------------------------------------------------+-------------------------------------------------------+

Configuration
-------------

Change the default locale provider service ID to the provider which fulfill your needs.

.. code-block:: yaml

    sulu_website:
        default_locale:
            provider_service_id: sulu_website.default_locale.request_provider
