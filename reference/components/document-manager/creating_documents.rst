Creating Documents
==================

The Sulu Document Manager allows you to map any existing class to a PHPCR
node.

The Document
------------

First, create a class with some fields you would like to map:

.. code-block:: php

    class SomeDocument
    {
        private $title;
        private $date;

        public function setTitle($title)
        {
            $this->title = $title;
        }

        public function setDate($date)
        {
            $this->date = $date;
        }

        public function getTitle()
        {
            return $this->title;
        }

        public function getDate()
        {
            return $this->date;
        }
    }

Defining the alias and type and field mapping
---------------------------------------------

In order for the document manager to recognize existing managed documents and
persist new ones, you must add some mapping to your configuration.

This configuration can be done in you applications ``config.yml`` file:

.. code-block:: yaml

    sulu_document_manager:
        mapping:
            # ...
            my_new_document: 
                phpcr_type: acme:somedocument
                class: Acme\Bundle\TitleBundle\Document\SomeDocument
                mapping:
                    title:
                        encoding: content # optional
                        type: string # optional
                        property: title # optional
                    date:
                        encoding: content
                        type: string

Above we define the following:

1. The alias of the document as ``my_new_document``. This alias can
   be used instead of the long class name when managing the document.

2. A PHPCR type which will be used to map the document to the class
   name.

3. The class which should be managed.

4. That ``title`` should be mapped to the ``title`` property in the PHPCR node,
   encoded with the ``content`` strategy and its type should be ``string``.

5. That ``date`` should be encoded  using the ``content`` encoding strategy
   and its type should be ``date``.

For more information about mapping (including encoding) see :doc:`mapping`.

Adding Behaviors
----------------

Behaviors are interfaces that you can add to a document which add extra
functionality.

One such behavior is the ``TimestampBehavior``. You can add it as follows:

.. code-block:: php

    use Sulu\Component\DocumentManager\Behavior\Audit\TimestampBehavior;

    class SomeDocument implements TimestampBehavior
    {
        // ...

        private $changed;
        private $created;

        // ...

        public function getChanged()
        {
            return $this->changed;
        }

        public function getCreated()
        {
            return $this->changed;
        }
    }

Now, when you persist the document the ``created`` field will be set to a
``DateTime`` object with the value of todays date. When you update an existing
document only the ``changed`` field will be updated.

.. note::

    Some behaviors, such as the ``TimestampBehavior`` use reflection to set
    object properties. This is because having setter methods would not be
    appropriate.

    The DocumentManager will expect these properties to be set, if they are
    not it will throw an Exception explaining which properties need to be
    added.
