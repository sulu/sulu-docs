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
* `PHP`_ 8.2 or higher
* the `dom_extension`_ and `xml_extension`_ for PHP
* the `gd`_, `imagick_extension`_ or `vips_extension`_ for PHP
* the `pdo_sqlite`_ when using `Loupe`_ as the `SEAL`_ Search Engine Adapter
* a `database management system supported by Doctrine`_
* `Composer`_

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
.. _pdo_sqlite: https://www.php.net/manual/en/ref.pdo-sqlite.php
.. _Loupe: https://github.com/loupe-php/loupe
.. _SEAL: https://github.com/PHP-CMSIG/search
.. _vips_extension: https://github.com/libvips/php-vips-ext
.. _database management system supported by Doctrine: https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/platforms.html
.. _Composer: https://getcomposer.org
.. _Node.js: http://nodejs.org
.. _npm: https://www.npmjs.com
