Sulu Document Manager
=====================

The Sulu Document Manager is a layer which sits between the PHPCR repository
and the application model. It provides a layer of domain abstraction on top of
the raw PHPCR session, workspace, query manager, etc.

It is similar in concept to a typical ORM (for example Doctrine ORM) with some
differences.

The following is an example:

.. code-block:: php

    <?php
    // find a document in a specific locale and set a new title
    $document = $documentManager->find('/cmf/contents/foobar', 'de');
    $document->setTitle('Hello');

    // persist the document then flush the changes
    $documentManager->persist($document);
    $documentManager->flush();

If you are familiar with Doctrine this will seem very familiar. There are some
differences however:

- Persist commits the changes to the *node* immediately, changes made to the
  document later on will not be taken into account on `flush()`. It is better
  to think of `persist()` as a function which prepares a `snapshot` of the
  current state of the document to be persisted.

- The document manager is localization aware by default.

Some other things to note:

- The Document Manager is 100% event based. This makes it very extensible, all
  of the functionality is provided by Event Subscribers.

- Documents are defined with "Behavior" interfaces, which the event
  subscribers use to determine if and how the document should be handled.
