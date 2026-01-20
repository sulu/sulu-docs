Adding localizations
====================

Sulu is built for companies with an international focus, so translating pages
into multiple different languages is a very important task for a content editor
using Sulu. Sulu also considers different language variations across different countries.
The combination of these two factors is called a localization.

Configuring webspace localizations
----------------------------------

Localizations for the content are configured in the webspaces, as already
described in :doc:`webspaces`. Adding another localization is as simple as
adding another ``localization`` tag to the webspace configuration file.
Localizations can also be nested, which has no impact on the representation in
all the dropdowns, but it will help the system to find better fallbacks.

For example, using English and German as a language might look something
like the following fragment.

.. code-block:: xml

    <localizations>
        <localization language="en">
            <localization language="en" country="us"/>
            <localization language="en" country="gb"/>
        </localization>
        <localization language="de">
            <localization language="de" country="de"/>
            <localization language="de" country="at"/>
            <localization language="de" country="ch"/>
        </localization>
    </localizations>

With this configuration the system will contain seven different content
localizations: ``en``, ``en-us``, ``en-gb``, ``de``, ``de-de``, ``de-at``,
``de-ch``, whereby ``en-us`` and ``en-gb`` are falling back to ``en``, and
``de-de``, ``de-at`` and ``de-ch`` are falling back to ``de``.

After adding localizations in the webspace, you need to run

.. code-block:: bash

    php bin/adminconsole sulu:page:initialize

This will reinitialize the page content tree and prepare the system to handle
the newly added localization. Afterwards, no user will have permissions for the
new localization, so make sure to assign them in the contact permissions tab.

.. note::

    When adding a new localization you have to make sure that the localization
    is also covered by one of the webspaces URLs. This can either happen by using
    the `language` and `country` attributes of the `url` tag, or by specifying
    e.g. the `{localization}` placeholder. For more information see
    `Setup a Webspace <webspaces.html#urls>`_.

Adding custom localizations
---------------------------

There is another possibility for adding localizations not related to a webspace.
More details can be found in :doc:`../cookbook/localization-provider`

Usage of localizations
----------------------

For the developer, the only contact points with localizations are the
configuration and the potential use of a language switcher on the homepage.
For the language switcher, you can use the ``localizations`` variable delivered to the twig template,
which contains an associative array with the parameters ``locale``, ``url`` and ``country``.
The currently active locale can be obtained from the underlying Symfony Request
object with ``app.locale``.

.. code-block:: twig

    <ul>
    {% for localization in localizations %}
        <li><a href="{{ localization.url }}">{{ localization.locale }}</a>
    {% endfor %}
    </ul>

.. important::

    The ``localizations`` variable contains the page URLs based on the URLs configured in 
    the  ``<urls>`` section of your webspace. Have a look at :doc:`../book/webspaces` for
    more information about the webspace configuration.

The template itself does not have to be adapted for usage with multiple
localizations. The twig variables are the same for every language, only the
content is different, and this is handled by Sulu for the developer.

The nesting of the localizations is very important for the column navigation of
the content. In case there is a ghost page - which means that the page is not
translated into the current localization - this tree will be used to determine
the "closest" language available.
