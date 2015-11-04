SearchBundle
============

The SuluSearchBundle is mainly an integration of the `MassiveSearchBundle`_
into Sulu. There is also the `MassiveSearchBundle Documentation`_ explaining
this Bundle in more detail. This documentation is also valid for using it with
Sulu, although there are some extensions made by the SuluSearchBundle. It
offers a controller to provide a web API to search through the system, adds
more fields - like creator and changer - to the search documents and also
handles the security provided by Sulu. But the most important thing is, that it
also contains the UI for the administration interface.

Configuration
-------------

The configuration of this bundle contains some more metadata about each index
created by the `MassiveSearchBundle`_. There are two optional values, meaning
both of them could be omitted:

- ``name``: Can contain a name for the index, which will be used in the UI.
  Useful if the index represents something a non-translatable literal can
  describe.
- ``security_context``: This setting is used to describe which security context
  (see :doc:`security`) the user has to have ``view`` permission in, in order
  to search through this index.

So a sample configuration would look like this:

.. code-block:: yaml
    
    sulu_search:
        indexes:
            contact:
                security_context: sulu.contacts.people

The values under ``indexes``, namely ``contact`` in this example, result from
the index tag in the `mapping configuration`_. The value of
``security_context`` indicates that the user has to have the permission to view
the ``sulu.contacts.people`` security context to see result from the
``contact`` index.

.. note::

    It is also possible to use `PrependExtensions`_ or in more complicated
    cases to change the value of the ``sulu_search.indexes`` parameter in a
    `CompilerPass`_. Actually that is what most of the Sulu bundles are doing
    to minimize the configuration effort of the application.

That would be enough for the user to retrieve some search results, but a click
on these results would not lead to the corresponding form. So the last missing
step is to tell the system how to load this form when clicking on a search
result. This has to be done in the main javascript entry point of the bundle,
which is located at ``Resources/public/js/main.js``. A line like the following
has to be added to the ``initialize`` method:

.. code-block:: javascript

    app.sandbox.urlManager.setUrl(
        'contact',
        'contacts/contacts/edit:<%= id %>/details'
    );

This line tells the `urlManager`, which is responsible for creating the URLs
for the search, how the entry from the search has to be resolved. The variables
from the search document can also be used in the template, which is passed as
the second argument.

For more complex cases there are two more parameters in the ``setUrl``
function, which are shown in the next example.

.. code-block:: javascript

    sandbox.urlManager.setUrl(
        'page',
        function(data) {
            return 'content/contents/<%= webspace %>/<%= locale %>/edit:<%= id %>/content';
        },
        function(data) {
            return {
                id: data.id,
                webspace: data.properties.webspace_key,
                url: data.url,
                locale: data.locale
            };
        },
        function (key) {
            if (key.indexOf('page_') === 0) {
                return 'page';
            }
        }
    );

The first argument is again the name of the index, the second one a function,
returning a string for the javascript template engine. The third is a handler
returning a new object from the given data, which will passed to the template
engine in combination with the string being returned from the second argument.
Finally, the fourth argument is the key modifier, which takes the name of the
index, and can modify it, before it is compared with the first argument from
all ``setUrl`` calls. This is especially useful if the `ExpressionLanguage`_
is used for the generation of the index name.

.. _MassiveSearchBundle: https://github.com/massiveart/MassiveSearchBundle
.. _MassiveSearchBundle Documentation: http://massivesearchbundle.readthedocs.org/en/latest/
.. _mapping configuration: http://massivesearchbundle.readthedocs.org/en/latest/mapping.html
.. _PrependExtensions: http://symfony.com/doc/current/cookbook/bundles/prepend_extension.html
.. _CompilerPass: http://symfony.com/doc/current/cookbook/service_container/compiler_passes.html
.. _ExpressionLanguage: http://massivesearchbundle.readthedocs.org/en/latest/mapping.html#expression-language

