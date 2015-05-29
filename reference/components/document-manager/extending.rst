Extending the Document Manager
==============================

Where to put things?
--------------------

Any classes which relate to the documents or the document manager should
first, by convention, be placed within a `Document` namespace.

Documents themselves should be placed directly under this namespace and other
types of class should be placed in sub namespaces with appropriate names. For
example:

````bash
src/Bundle/MyBundle/Document/FooDocument.php
src/Bundle/MyBundle/Document/Initializer/FooInitializer.php
src/Bundle/MyBundle/Document/Subscriber/FooSubscriber.php
````
