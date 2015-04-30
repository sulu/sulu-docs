Subscribers
===========

Subscribers perform all of the hard work in the Sulu Document Manager.

Persistance

Debugging Subscribers
---------------------

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

A full list of events can be retrived if you ommit the argument:


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
