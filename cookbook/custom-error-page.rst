Custom error page
=================

With Sulu it is very easy to customize the error pages for you website users.
You can define for each http-status-code an own template.

Configuration
-------------

The following code-block shows you the default configuration for the excption
templates. If you want to add an own exception for example 400 you can simply
add it to the list.

.. code-block:: twig

	sulu_website:
	    error_templates:
	        404: ClientWebsiteBundle:templates:error404.html.twig
	        default: ClientWebsiteBundle:templates:error.html.twig

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

* `statusCode`: http-status-code
* `status_code`: same as `statusCode` but it is needed to be compatible to the symfony exception template
* `statusText`: http-status-code message
* `status_text`: same as `statusText` but it is needed to be compatible to the symfony exception template
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
