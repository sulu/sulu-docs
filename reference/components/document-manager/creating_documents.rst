Creating Documents
==================

The Sulu Document Manager primarily uses interfaces to define mapping or
behaviors, although because of its event based nature, it is equally possible
to implement what is now the conventional mapping pattern using XML, YAML,
annotations or whatever.

Things to note:

- When you implement an interface which populates or persists properties of
  your class, you will normally need to add specifically named properties. If
  you do not, an error will be thrown.

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

The above document will the nodes path, UUID and node name populated.


Defining the alias and type
---------------------------

In order for the document manager to recognize existing managed documents and
persist new ones, you must define some mapping.

If you are using the Document Manager in the context of Sulu, this
configuration can be done in you applications ``config.yml`` file:

.. code-block:: yaml

    sulu_document_manager:
        mapping:
            # ...
            my_new_document: 
                phpcr_type: acme:somedocument
                class: Acme\Bundle\FooBundle\Document\SomeDocument

Above define three things:

1. The alias of the document as ``my_new_document``. This alias can
   be used instead of the long class name when managing the document.

2. A PHPCR type which will be used to map the document to the class
   name.

3. The class which should be managed.
