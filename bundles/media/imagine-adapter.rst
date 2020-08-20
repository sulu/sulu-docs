Imagine adapter
===============

The rendering engine for media is able to use different imagine adapters. By default the bundle detects the installed
adapters and uses the most mature implementation by default.

Available adapters are:

* `gd`_
* `imagick_extension`_
* `vips_extension`_

GD
--

The simplest adapter is GD and it has no external dependencies besides the PHP extension.

Imagick
-------

This extension uses imagemagick to render the images.

To install it use following commands:

**Mac:**

.. code-block:: bash

    brew install imagemagick
    pecl install imagick

**Linux:**

.. code-block:: bash

    apt-get install libmagickwand-dev inkscape
    pecl install imagick

VIPS
----

This extension uses vips to render the images.

To install it use following commands:

**Mac:**

.. code-block:: bash

    brew install pkg-config
    brew install vips
    pecl install vips
    composer require rokka/imagine-vips

**Linux:**

.. code-block:: bash

    apt-get install libvips-dev
    pecl install vips
    composer require rokka/imagine-vips

.. _gd: http://php.net/manual/en/book.image.php
.. _imagick_extension: http://php.net/manual/en/book.imagick.php
.. _vips_extension: https://github.com/libvips/php-vips-ext
