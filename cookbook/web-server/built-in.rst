How to Use PHP's built-in Web Server
====================================

PHP (>= 5.4) comes with a `built-in web server`_. This server can be used to
run your Sulu application during development. This way, you don't have to bother
configuring a full-featured web server such as :doc:`Apache <apache>` or
:doc:`Nginx <nginx>`.

.. caution::

    The built-in web server is meant to be run in a controlled environment. It
    is not designed to be used on public networks.

The server can be started with the ``server:start`` command. You will have to
start two different servers for the administration and the website:

.. code-block:: bash

    bin/adminconsole server:start
    bin/websiteconsole server:start

These commands will start two servers listening on http://127.0.0.1:8000 and
http://127.0.0.1:8001 in the background.

You can change the IP and port of the web servers by passing them as argument:

.. code-block:: bash

    bin/adminconsole server:start 192.168.0.1:8080
    bin/websiteconsole server:start 192.168.0.1:8081

The server can be stopped again with the ``server:stop`` command:

.. code-block:: bash

    bin/adminconsole server:stop
    bin/websiteconsole server:stop

Read the `Symfony documentation on the built-in web server`_ to learn more about
the different ``server:*`` commands and options.

.. _built-in web server: http://www.php.net/manual/en/features.commandline.webserver.php
.. _Symfony documentation on the built-in web server: http://symfony.com/doc/current/setup/built_in_web_server.html
