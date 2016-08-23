Nginx
=====

The Nginx configuration could look something like.

.. code-block:: nginx

  server {
      listen 8081;

      server_name sulu.lo;
      root /var/www/sulu.lo/web;

      error_log /var/log/nginx/sulu.lo.error.log;
      access_log /var/log/nginx/sulu.lo.at.access.log;

      # strip app.php/ prefix if it is present
      rewrite ^/app\.php/?(.*)$ /$1 permanent;

      location /admin {
          index admin.php;
          try_files $uri @rewriteadmin;
      }

      location @rewriteadmin {
          rewrite ^(.*)$ /admin.php/$1 last;
      }

      location / {
        index website.php;
        try_files $uri @rewritewebsite;
      }

      # expire
      location ~* \.(?:ico|css|js|gif|jpe?g|png)$ {
          try_files $uri /website.php/$1;
          access_log off;
          expires 30d;
          add_header Pragma public;
          add_header Cache-Control "public";
      }

      location @rewritewebsite {
          rewrite ^(.*)$ /website.php/$1 last;
      }

      # pass the PHP scripts to FastCGI server from upstream phpfcgi
      location ~ ^/(website|admin|app|app_dev|config)\.php(/|$) {
          include fastcgi_params;
          fastcgi_pass unix:/var/run/php5-fpm.sock;
          fastcgi_buffers 16 16k;
          fastcgi_buffer_size 32k;
          fastcgi_split_path_info ^(.+\.php)(/.*)$;
          fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
          fastcgi_param SYMFONY_ENV dev;
      }
  }

.. warning::
    Be sure to also configure your local host-file, if running Sulu locally.

.. include:: file-permissions.inc.rst

