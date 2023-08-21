System Requirements for Running Sulu
====================================

Sulu is built on the shoulders of Giants. This page describes the requirements
your system needs to fulfill when you run a Sulu application.

Mandatory Requirements
----------------------

The following requirements *must* be met to run Sulu:

* Mac OSX, Linux or Windows
* :doc:`Apache <../cookbook/web-server/apache>` or
  :doc:`Nginx <../cookbook/web-server/nginx>` with enabled URL rewriting
* `PHP`_ 8.1 or higher
* the `dom_extension`_ and `xml_extension`_ for PHP
* the `gd`_, `imagick_extension`_ or `vips_extension`_ for PHP
* a `database management system supported by Doctrine`_
* `Composer`_

Optional Requirements
------------------------

The following requirements are optional:

* `Apache Jackrabbit`_ when :doc:`../bundles/page/versioning` of content is needed

Development Requirements
------------------------

If you want to build parts of the system on your own, you will additionally need:

* `Node.js`_
* `npm`_ 6

.. _PHP: http://php.net
.. _xml_extension: http://php.net/manual/en/book.xml.php
.. _dom_extension: http://php.net/manual/en/book.dom.php
.. _gd: http://php.net/manual/en/book.image.php
.. _imagick_extension: http://php.net/manual/en/book.imagick.php
.. _vips_extension: https://github.com/libvips/php-vips-ext
.. _database management system supported by Doctrine: https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/platforms.html
.. _Composer: https://getcomposer.org
.. _Apache Jackrabbit: http://jackrabbit.apache.org
.. _Node.js: http://nodejs.org
.. _npm: https://www.npmjs.com
