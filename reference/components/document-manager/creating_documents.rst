Creating Documents
==================

The Sulu Document Manager uses interfaces to apply mapping or behaviors to
documents (i.e. objects). These interfaces are known as *behaviors*. Behaviors
acts upon documents.

.. note::

    It is equally possible to implement what is now the conventional mapping
    pattern using XML, YAML, annotation, etc. But for now only behavioral
    interfaces are supported.

The File
--------

Because the Document Manager uses interfaces and does not depend on metadata
mapping you can put your document anywhere you want without changing any
configuration:

.. code-block:: php

    <?php

    namespace Acme\Bundle\FooBundle\Document;

    use Sulu\Component\DocumentManager\Behavior\Mapping\NodeNameBehavior;
    use Sulu\Component\DocumentManager\Behavior\Mapping\PathBehavior;
    use Sulu\Component\DocumentManager\Behavior\Mapping\UuidBehavior;

    class SomeDocument implements
        NodeNameBehavior,
        PathBehavior,
        UuidBehavior,
    {
        private $nodeName;
        private $path;
        private $uuid;
        private $targetDocument;

        public function getNodeName() 
        {
            return $this->nodeName;
        }

        public function getPath() 
        {
            return $this->path;
        }

        public function getUuid() 
        {
            return $this->uuid;
        }
    }

The above document will have the nodes path, UUID and node name populated. The
properties are mandatory and the behaviors will expect them to be there, if
they are not then an exception will be thrown explaining which properties need
to be added.

.. note::

    The behaviors will often use Reflection to set the value of an objects
    properties, bypassing any protection that property may have.


Defining the alias and type
---------------------------

In order for the document manager to recognize existing managed documents and
persist new ones, you must add some mapping to your configuration.

This configuration can be done in you applications ``config.yml`` file:

.. code-block:: yaml

    sulu_document_manager:
        mapping:
            # ...
            my_new_document: 
                phpcr_type: acme:somedocument
                class: Acme\Bundle\FooBundle\Document\SomeDocument

Above we define three things:

1. The alias of the document as ``my_new_document``. This alias can
   be used instead of the long class name when managing the document.

2. A PHPCR type which will be used to map the document to the class
   name.

3. The class which should be managed.
