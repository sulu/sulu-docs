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
* `PHP`_ 5.5 or higher
* the `intl extension`_ for PHP
* the `fileinfo` extension for PHP
* the `gd`_ or `imagick extension`_ for PHP
* a `database management system supported by Doctrine`_
* `Composer`_

Recommended Requirements
------------------------

The following requirements are optional, but recommended for using Sulu in
production:

* `Apache Jackrabbit`_

Development Requirements
------------------------

If you want to build parts of the system on your own, you will additionally need:

* `Node.js`_
* `Grunt`_
* `Ruby`_
* `Compass`_

.. _PHP: http://php.net
.. _intl extension: http://php.net/manual/en/book.intl.php
.. _gd: http://php.net/manual/en/book.image.php
.. _imagick extension: http://php.net/manual/en/book.imagick.php
.. _database management system supported by Doctrine: http://doctrine-orm.readthedocs.io/projects/doctrine-dbal/en/latest/reference/platforms.html
.. _Composer: https://getcomposer.org
.. _Apache Jackrabbit: http://jackrabbit.apache.org
.. _Node.js: http://nodejs.org
.. _Grunt: http://gruntjs.com
.. _Ruby: https://www.ruby-lang.org/en/
.. _Compass: http://compass-style.org
