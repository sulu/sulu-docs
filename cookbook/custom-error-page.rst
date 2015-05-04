Custom error page
=================

With Sulu it is very easy to customize the error pages for you website users.
You can define for each http-status-code an own template.

Configuration
-------------

The following code-block shows a default configuration for the exception
templates. If you want to add an own exception for example 400 you can simply
add it to the list. You can specify that for each webspace.

.. code-block:: xml

    <theme>
       <key>default</key>

        <error-templates>
            <error-template default="true">ClientWebsiteBundle:views:error.html.twig</error-template>
            <error-template code="404">ClientWebsiteBundle:views:error404.html.twig</error-template>
            <error-template code="500">ClientWebsiteBundle:views:error500.html.twig</error-template>
        </error-templates>
    </theme>

The `ExceptionController` uses the status-code of the response to determine
which template is responsible for the exception. If no special template is
defined it uses the `default` template.

Twig-Template
-------------

In the twig-template you can use your website master template to reuse your
style.

.. code-block:: html

	{% extends "ClientWebsiteBundle:views:master.html.twig" %}

	{% block title %}Error {{ status_code }}{% endblock %}

	{% block content %}
	    <h1>Error {{ status_code }}</h1>
	    <p>{{ status_text }}</p>

	    <p>{{ exception.message }}</p>
	{% endblock %}

.. note::

    Be carefully which variable you use in your `master.html.twig`.

Following variables are usable inside the exception template.

* `status_code`: http-status-code
* `status_text`: http-status-code message
* `exception`: complete exception object
* `currentContent`: repsonse content which were rendered bofore exception was thrown
* `urls`: localized urls to start page (e.g. for language-switcher)
* `request`: 
  * `webspaceKey`
  * `defaultLocale`
  * `locale`
  * `portalUrl`
  * `resourceLocatorPrefix`
  * `resourcelocator
  * `get`: array of get paramters
  * `post`: array of post paramters
  * `analyticsKey`

Test it
-------

To test your error pages you can use following routes:

.. code-block::

    {portal-prefix}/_error/{statusCode}

.. note::

    If your not sure about your portal config you can get the routes with this 
    `app/webconsole router:debug | grep _error` command

Examples:

.. code-block::

	 sulu.lo/ch._twig_error_test       ANY    ANY    sulu.lo /ch/_error/{code}.{_format}
	 sulu.lo/en._twig_error_test       ANY    ANY    sulu.lo /en/_error/{code}.{_format}
	 sulu.lo/fr._twig_error_test       ANY    ANY    sulu.lo /fr/_error/{code}.{_format}
	 sulu.lo/de._twig_error_test       ANY    ANY    sulu.lo /de/_error/{code}.{_format}
	 sulu.lo._twig_error_test          ANY    ANY    sulu.lo /_error/{code}.{_format}


