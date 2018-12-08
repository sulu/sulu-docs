Nginx
=====

The Nginx configuration could look something like.

.. code-block:: nginx

  server {
      listen 80;

      server_name sulu.lo;
      root /var/www/sulu.lo/public;

      error_log /var/log/nginx/sulu.lo.error.log;
      access_log /var/log/nginx/sulu.lo.at.access.log;

      location / {
          # try to serve file directly, fallback to index.php
          try_files $uri /index.php$is_args$args;
      }

      # expire
      location ~* \.(?:ico|css|js|gif|jpe?g|png|svg|woff|woff2|eot|ttf)$ {
          # try to serve file directly, fallback to index.php
          try_files $uri /index.php$is_args$args;
          access_log off;
          expires 30d;
          add_header Pragma public;
          add_header Cache-Control "public";
      }

      # pass the PHP scripts to FastCGI server from upstream phpfcgi
      location ~ ^/(index|config)\.php(/|$) {
          fastcgi_pass unix:/var/run/php/php7.1-fpm.sock;
          fastcgi_split_path_info ^(.+\.php)(/.*)$;
          include fastcgi_params;
          fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
          fastcgi_param DOCUMENT_ROOT $realpath_root;
          internal;
      }
  }

.. warning::
    Be sure to also configure your local host-file, if running Sulu locally.

.. include:: file-permissions.inc.rst

