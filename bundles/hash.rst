HashBundle
==========

The SuluHashBundle adds an easy to use solution for protecting entities and
documents from being overridden by accident. This can happen if two users open
the same form in the user interface. If the user which opened the form later
saves its changes first, the later save will cause the changes to be
overridden.

Serialization
-------------

The `HashSerializeEventSubscriber` is the most important part of the
HashBundle. The subscriber's job is to add the hash of the object to the
serialized representation being delivered in the response. So the serializer
has to be used to deliver the response with the hash value automatically.

Hashing
-------

The `HashSerializeEventSubscriber` uses the `AuditableHasher` for hashing
documents and entities. This hasher calculates a hash based on the user who
modified a document and the time when that change happened. So this only works
if the `AuditableInterface` (for entities) or the `AuditableBehavior` (for
documents) is implemented in the given object.

Validation
----------

This is usually done by the controller itself. The SuluHashBundle comes with a
service called `sulu_hash.request_hash_checker`, which has a `checkHash` method
taking the request, the object and the identifier of an object (since the
decoupled component cannot know how to get the identifier of the object).

This method will extract the `_hash` parameter from the request and compare it
with a newly created hash from the passed object, and throw an
`InvalidHashException` in case the hashes do not match. The SuluHashBundle also
prepends the configuration of the `FOSRestBundle`_ so that it recognizes the
Exception and returns a response code with the status code `409 (CONFLICT)`. It
also adds a code of `1103` to the Response, so that certain actions like
showing an overlay asking if the changes should be overridden are possible.

The hash checker also checks the value of the query parameter `force`, which
allows to override the object even if it has changed in the mean time.

The following code snippet shows how the hash checker can be included in a
controller:

.. code-block:: php

    $this->get('sulu_hash.request_hash_checker')
        ->checkHash($request, $object, $object->getId());

.. _FOSRestBundle: http://symfony.com/doc/current/bundles/FOSRestBundle/4-exception-controller-support.html

