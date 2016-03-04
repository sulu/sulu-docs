Publishing
==========

Publishing is the act of making a certain content public - visible - on your
website.

Configuring a "live" workspace
------------------------------

Sulu will automatically syncronize documents with the live (or publish)
workspace. Three steps need to be taken:

1. Configure a new PHPCR session for the publish document manager.
2. Define a new document manager and map it to the new PHPCR session.
3. Configure Sulu to syncronize documents to the new document manager.

Adding a PHPCR workspace
""""""""""""""""""""""""

First of all you will need to configure a new PHPCR workspace as follows:

.. code-block:: yaml

    doctrine_phpcr:
        session:
            sessions:
                # ...
                live:
                    backend:
                        check_login_on_server: false
                        type: jackrabbit
                        url:  "%phpcr_backend_url%"
                    workspace: live
                    username: "%phpcr_username%"
                    password: "%phpcr_password%"

TODO: Ensure that adding the above configuration works, also note that we
should update the configuration to use the "complete" form rather than the
"short" convenient form of defining the session.

Defining a new document manager
"""""""""""""""""""""""""""""""

A new document manager can be defined as follows:

.. code:: yaml

    sulu_document_manager:
        # ...
        managers:
            # ...
            live:
                session: live

Here ``live`` is the name of the document manager, and it will use the
``live`` session as defined in the previous step.

Configuring Sulu to publish to the new document manager
"""""""""""""""""""""""""""""""""""""""""""""""""""""""

The storage layer is now ready, but as of yet the documents will not be
publushed to the new document manager - you need to tell sulu *which* document
manager it should sync to as follows:

.. code-block:: yaml

    sulu_content:
        # ...
        publish:
            document_manager: live

Where ``live`` is the name of the document manager defined in the previous
step.
