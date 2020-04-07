How to Use PHP's built-in Web Server
====================================

.. note::

    Symfony provides a more feature rich web server for development.
    Read the `Symfony documentation on the symfony server`_ to learn more about.

PHP (>= 5.4) comes with a `built-in web server`_. This server can be used to
run your Sulu application during development. This way, you don't have to bother
configuring a full-featured web server such as :doc:`Apache <apache>` or
:doc:`Nginx <nginx>`.

.. caution::

    The built-in web server is meant to be run in a controlled environment. It
    is not designed to be used on public networks.

The server can be started with the following command.

.. code-block:: bash

    php -S localhost:8000 -t public/ config/router.php

These commands will start a server listening on http://127.0.0.1:8000, resp.
the first free port after 8000.

You can change the IP and port of the web servers by passing them as argument:

.. code-block:: bash

    php -S 192.168.0.1:8080 -t public/ config/router.php

.. _built-in web server: http://www.php.net/manual/en/features.commandline.webserver.php
.. _Symfony documentation on the symfony server: https://symfony.com/doc/current/setup/symfony_server.html
