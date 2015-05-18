Using the Document Manager
==========================

Finding documents
-----------------

Documents can be located using either their UUID or their path:

.. code-block:: php

    <?php
    $document = $documentManager->find('/path/to/document');
    $document = $documentManager->find('842e61c0-09ab-42a9-87c0-308ccc90e6f4');

To find a localized document:

.. code-block:: php

    $germanDocument = $documentManager->find('842e61c0-09ab-42a9-87c0-308ccc90e6f4', 'de');

Additionally, options can be specified:

.. code-block:: php

    <?php
    $fooDocument = $documentManager->find('842e61c0-09ab-42a9-87c0-308ccc90e6f4', 'de', array(
        'my_option' => 'foobar',
    ));

Persisting documents
--------------------

The Sulu Document Manager requires that you ``persist()`` documents and then
``flush`` the document manager.

.. note::

    The ``persist`` operation, unlike other document/object managers, takes a snapshot of
    the document in its current state and maps the data to the PHPCR node.

    Changes made to the document after calling ``persist`` will not be taken
    in to account when ``flush`` is called.

Below is a simple persist operation:

.. code-block:: php

    <?php
    $document = new MyDocument();
    $document->setTitle();

    $documentManager->persist($document, 'fr', array(
        'path' => '/path/to/persist/to',
    ));

    $documentManager->flush();

This persists the document in the French language at the path. The path is
given as an **option**. The ``path`` option comes from the
``ExplicitPathSubscriber`` subscriber. The amount of options available depends
on which subscribers you have registered.

See the :doc:`subscribers` chapter for more information.
