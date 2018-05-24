Debugging
=========

One of the disadvantages of an event based system is that tracking what
happens and when it happens can be tricky. The Document Manager provides some
tools to ameliorate this problem.

Subscriber Debug Command
------------------------

It is often useful to know which subscribers are being called and the order in
which they are called. If you are using Sulu, then this can be achieved via
the following command:

.. code-block:: bash

    $ ./app/console sulu:document:subscriber:debug remove
    +--------------------------------------------------------------------------+------------------+----------+
    | Class                                                                    | Method           | Priority |
    +--------------------------------------------------------------------------+------------------+----------+
    | Sulu\Bundle\SearchBundle\EventListener\ContentSubscriber                 | handlePreRemove  | 600      |
    | Sulu\Component\Content\Document\Subscriber\ContentRemoveSubscriber       | handleRemove     | 550      |
    | Sulu\Component\DocumentManager\Subscriber\Phpcr\RemoveSubscriber         | handleRemove     | 500      |
    | Sulu\Component\Content\Document\Subscriber\Compat\MapperRemoveSubscriber | handlePreRemove  | 500      |
    | Sulu\Component\DocumentManager\Subscriber\Core\RegistratorSubscriber     | handleRemove     | 490      |
    | Sulu\Bundle\SearchBundle\EventListener\ContentSubscriber                 | handlePostRemove | -100     |
    | Sulu\Component\Content\Document\Subscriber\Compat\MapperRemoveSubscriber | handlePostRemove | -100     |
    +--------------------------------------------------------------------------+------------------+----------+

Here we list all of the subscribers which will be executed when a `remove`
event is fired.

A full list of events can be retrieved if you omit the argument:

.. code-block:: bash

    $ ./app/console sulu:document:subscriber:debug
    +----------------------+
    | Event                |
    +----------------------+
    | persist              |
    | hydrate              |
    | remove               |
    | refresh              |
    | copy                 |
    | move                 |
    | create               |
    | clear                |
    | find                 |
    | reorder              |
    | flush                |
    | query.create         |
    | query.create_builder |
    | query.execute        |
    | configure_options    |
    +----------------------+

Logging
-------

The Document Manager provides detailed logging about which subscribers are
executed, the state of the event and the time taken by each event to be
executed, for example:


.. code-block:: bash

    0.012195 S\C\C\D\S\ExtensionSubscriber           handlePersist        n:/cmf/sulu_io/contents/test1 d:0000000021b01e32000000005bcf8fba l:en p:/cmf/sulu_io/contents
    0.000000 S\C\C\D\S\WebspaceSubscriber            handlePersist        n:/cmf/sulu_io/contents/test1 d:0000000021b01e32000000005bcf8fba l:en p:/cmf/sulu_io/contents
    0.076923 S\C\C\D\S\ContentSubscriber             handlePersist        n:/cmf/sulu_io/contents/test1 d:0000000021b01e32000000005bcf8fba l:en p:/cmf/sulu_io/contents
    0.000000 S\C\C\D\S\NavigationContextSubscriber   handlePersist        n:/cmf/sulu_io/contents/test1 d:0000000021b01e32000000005bcf8fba l:en p:/cmf/sulu_io/contents
    0.000000 S\C\C\D\S\RedirectTypeSubscriber        handlePersist        n:/cmf/sulu_io/contents/test1 d:0000000021b01e32000000005bcf8fba l:en p:/cmf/sulu_io/contents
    0.000000 S\C\C\D\S\WorkflowStageSubscriber       handlePersist        n:/cmf/sulu_io/contents/test1 d:0000000021b01e32000000005bcf8fba l:en p:/cmf/sulu_io/contents
    0.000000 S\C\C\D\S\OrderSubscriber               handlePersist        n:/cmf/sulu_io/contents/test1 d:0000000021b01e32000000005bcf8fba l:en p:/cmf/sulu_io/contents
    0.000000 S\C\C\D\S\RouteSubscriber               handlePersist        n:/cmf/sulu_io/contents/test1 d:0000000021b01e32000000005bcf8fba l:en p:/cmf/sulu_io/contents
    0.000000 S\C\D\S\B\A\BlameSubscriber             handlePersist        n:/cmf/sulu_io/contents/test1 d:0000000021b01e32000000005bcf8fba l:en p:/cmf/sulu_io/contents
    0.000000 S\C\D\S\B\A\TimestampSubscriber         handlePersist        n:/cmf/sulu_io/contents/test1 d:0000000021b01e32000000005bcf8fba l:en p:/cmf/sulu_io/contents
    0.000000 S\C\D\S\B\M\NodeNameSubscriber          handleNodeName       n:/cmf/sulu_io/contents/test1 d:0000000021b01e32000000005bcf8fba l:en p:/cmf/sulu_io/contents
    0.000000 S\C\D\S\B\M\UuidSubscriber              handleUuid           n:/cmf/sulu_io/contents/test1 d:0000000021b01e32000000005bcf8fba l:en p:/cmf/sulu_io/contents
    0.000000 S\C\D\S\B\M\ParentSubscriber            handleChangeParent   n:/cmf/sulu_io/contents/test1 d:0000000021b01e32000000005bcf8fba l:en p:/cmf/sulu_io/contents

Have a closer look:

.. code-block:: bash

    [1]      [2]                            [3]             [4]
    0.012195 S\C\C\D\S\ExtensionSubscriber  handlePersist   n:/cmf/sulu_io/contents/test1 d:0000000021b01e32000000005bcf8fba l:en

1. The time taken by the subscriber, expressed as a fraction of a second.
2. The class name. The namespace is compressed to allow for greater
   readability.
3. The method which handled the event
4. Event details, retrieved by the events ``getDebugMessage`` method.

The event details are context sensitive, the following lists all abbreviations:

- `n`: PHPCR Node path or UUID
- `d`: Document path or UUID
- `p`: Parent node path or UUID
- `l`: Locale
- `i`: Identifier (used for find events)
- `did`: Destination ID (used in copy/move events)
- `dnam`: Destination name (used in copy/move events)
- `after`: If a node should be ordered after or not (only for reorder events)

.. warning::

    Logging will slow down your application drastically. It should only be
    enabled in development environments.
