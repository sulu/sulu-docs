SearchBundle
============

The SuluSearchBundle is mainly an integration of the `MassiveSearchBundle`_
into Sulu. There is also the `MassiveSearchBundle Documentation`_ explaining
this Bundle in more detail. This documentation is also valid for using it with
Sulu, although there are some extensions made by the SuluSearchBundle. It
offers a controller to provide a web API to search through the system, adds
more fields - like creator and changer - to the search documents and also
handles the security provided by Sulu. But the most important thing is that it
also contains the administration user interface.

Configuration
-------------

The configuration of this bundle contains some more metadata about each index
created by the `MassiveSearchBundle`_ consisting of the following values:

- ``name``: Can contain a name for the index, which will be used in the UI.
  Useful if the index represents something a non-translatable literal can
  describe.
- ``security_context``: This setting is used to describe which security context
  (see :doc:`security`) the user has to have ``view`` permission in, in order
  to search through this index.
- ``icon``: Describes which icon should be used in the search result if no
  image is available.
- ``view``: Contains information necessary to navigate to the correct form on
  a click on the search result. This includes the ``name`` of the route and a
  map called ``result_to_view``, which maps fields from the search result to
  the edit form.

So a sample configuration would look like this:

.. code-block:: yaml

    sulu_search:
        indexes:
            contact:
                name: 'sulu_contact.people'
                icon: 'su-user'
                security_context: 'sulu.contacts.people'
                contexts: []
                view:
                    name: sulu_contact.contact_edit_form
                    result_to_view:
                        id: id
                        locale: locale

.. note::

    It is also possible to use `PrependExtensions`_ or in more complicated
    cases to change the value of the ``sulu_search.indexes`` parameter in a
    `CompilerPass`_. Actually that is what most of the Sulu bundles are doing
    to minimize the configuration effort of the application.

Website Search
^^^^^^^^^^^^^^

This bundle also provides configuration for the website search. By default,
when using the website search, only pages will be listed as result. If you
want e.g. a custom entity to be found in the website search, you have to
register the corresponding index.

The configuration looks like the following:

.. code-block:: yaml

    # config/packages/sulu_search.yaml

    sulu_search:
        website:
            indexes:
                - examples_published

You could also prevent pages from being found using the
search. This can be achieved using the following code:

.. code-block:: yaml

    # config/packages/sulu_search.yaml

    sulu_search:
        website:
            indexes:
                pages: null

If the index name contains the webspace key, you can use
the `#webspace#` placeholder, which will be automatically
replaced with the key of the current webspace.

An example for this would be the index for the pages:

.. code-block:: yaml

    # config/packages/sulu_search.yaml

    sulu_search:
        website:
            indexes:
                pages: page_#webspace#_published

Templating
----------

The SuluSearchBundle has a `WebsiteSearchController`, which loads the template
from the currently loaded webspace. It therefore uses the `RequestAnalyzer`,
and asks the webspace for its template of type ``search``. This template can
then be defined for every webspace in its XML configuration:

.. code-block:: xml

    <templates>
        <template type="search">ClientWebsiteBundle:views:search.html.twig</template>
    </templates>

See :doc:`../book/webspaces` for more details.

Reindexing
----------

Re-indexing is the process of reading all of the documents in the system and
regenerating their search records. This is necessary when changes are made to
the metadata and it is desirable to propagate these changes over all of the
indexed documents / entities in the system -- or when you import new data
(e.g. from a backup) and need to index that data.

To re-index all entities (Contacts, Media, etc.) and documents (Pages, Snippets)
simply run the following:

.. code-block:: bash

    $ php bin/console massive:search:reindex --env=prod

.. warning::

    At the moment it is required to also execute
    `php bin/websiteconsole massive:search:reindex --env=prod` to reindex the pages
    also for the website.

This may take anywhere between a minute and several hours depending on how
much data you have in your system.

To increase speed and reduce memory consumption:

- Use the ``--env=prod`` (see note below) switch to force the production settings: This will
  reduce logging and increase speed and lead to lower memory consumption.
- Ensure that the document manager has the ``debug: false`` option. This
  reduces logging dramatically.

To recover if the process is interrupted:

- You may *resume* the task simply by running it again.
- Use the ``--provider`` option to limit the reindexing to a certain reindex provider,
  for example ``--provider=doctrine_orm``.

.. important::

    In recomending the ``prod`` environment we assume that you have not
    changed the default environment configuration. The important point is that
    logging increases memory consumption and should be disabled.

.. _MassiveSearchBundle: https://github.com/massiveart/MassiveSearchBundle
.. _MassiveSearchBundle Documentation: http://massivesearchbundle.readthedocs.org/en/latest/
.. _mapping configuration: http://massivesearchbundle.readthedocs.org/en/latest/mapping.html
.. _PrependExtensions: http://symfony.com/doc/current/cookbook/bundles/prepend_extension.html
.. _CompilerPass: http://symfony.com/doc/current/cookbook/service_container/compiler_passes.html
.. _ExpressionLanguage: http://massivesearchbundle.readthedocs.org/en/latest/mapping.html#expression-language
.. _PHP 7: https://php.net
