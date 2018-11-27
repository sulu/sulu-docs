How to configure Websocket?
===========================

Sulu comes with a sulu-wide websocket application. This application is optional
but comes with a few feature which can only be used if the websocket is running.

For example the real-time notification for :doc:`../bundles/collaboration`
can only be displayed when the websocket is running.

Configuration
-------------

If you use the config layout of `sulu-standard
<https://github.com/sulu/sulu-standard>`_ you only have to set following
parameters in the ``parameters.yml`` file.

.. list-table::

    * - websocket_url
      - The url on which the websocket should listen.
    * - websocket_port
      - The port on which the websocket should listen.

If you have multiple installations of sulu on a single server be sure that you
use unique ports per installation.

If you have your own application, you can enable the websocket and configure its url and port
by placing the following lines into your configuration:

.. code-block:: yaml

    sulu_websocket:
        enabled: true
        server:
            http_host: "%websocket_url%"
            port: "%websocket_port%

Execution
---------

To execute the websocket you have to run the command the following command:

.. code-block:: bash

    php bin/console sulu:websocket:run -e prod`

This command starts the server and listens to the configured port.

We recommend to run the websocket with `supervisor <http://supervisord.org/>`_.
You can use the example configuration for supervisord.

**/etc/supervisor/conf.d/sulu_websocket.conf**

.. code-block:: ini

    [program:sulu_websocket]
    command=/var/www/sulu.io/bin/console sulu:websocket:run --env prod
    process_name=sulu_websocket
    user=www-data
    numprocs=1
    directory=/var/www/sulu.io
    autostart=true
    autorestart=true
    stderr_logfile=/var/log/supervisor/sulu_websocket.log
    stdout_logfile=/var/log/supervisor/sulu_websocket.log
    stdout_logfile_maxbytes=10MB
