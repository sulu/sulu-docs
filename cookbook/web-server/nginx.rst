Nginx
=====

The Nginx configuration could look something like.

The example shows a common production ready Nginx VHost using HTTPS via Letsencrypt.
It also forces all future requests to HTTPS via `Strict-Transport-Security` header.

.. code-block:: nginx

  server {
      listen 80;
      listen [::]:80;

      server_name example.org;

      return 301 https://$host$request_uri;
  }

  server {
      listen 443 ssl http2;
      listen [::]:443 ssl http2;

      server_name example.org;
      root /var/www/example.org/public;

      # SSL
      ssl_certificate /etc/letsencrypt/live/example.org/fullchain.pem; # managed by Certbot
      ssl_certificate_key /etc/letsencrypt/live/example.org/privkey.pem; # maanged by Certbot
      include /etc/letsencrypt/options-ssl-nginx.conf; # managed by Certbot
      ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem; # managed by Certbot
      # see: https://en.wikipedia.org/wiki/HTTP_Strict_Transport_Security
      add_header Strict-Transport-Security "max-age=15552000; preload";

      error_log /var/log/nginx/example.org.error.log;
      access_log /var/log/nginx/example.org.at.access.log;

	  # recommended security headers
      add_header X-Frame-Options sameorigin;
      add_header X-Content-Type-Options nosniff;
      add_header X-XSS-Protection "1; mode=block";

      location / {
          # try to serve file directly, fallback to index.php
          try_files $uri /index.php$is_args$args;
      }

      # expire
      location ~* \.(?:ico|css|js|gif|webp|avif|jpe?g|png|svg|woff|woff2|eot|ttf|mp4)$ {
          # try to serve file directly, fallback to index.php
          try_files $uri /index.php$is_args$args;
          access_log off;
          expires 1y;
          add_header Pragma public;
          add_header Cache-Control "public, immutable";
      }

      # pass the PHP scripts to FastCGI server from upstream phpfcgi
      location ~ ^/(index|config)\.php(/|$) {
          fastcgi_pass unix:/var/run/php/php8.5-fpm.sock; # replace with your used php version
          fastcgi_split_path_info ^(.+\.php)(/.*)$;
          include fastcgi_params;
          fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
          fastcgi_param DOCUMENT_ROOT $realpath_root;
          internal;
      }
  }

In your ``/etc/nginx/nginx.conf`` we recommend to enable gzip:

.. code-block:: nginx

        gzip on;

        gzip_vary on;
        gzip_proxied any;
        gzip_comp_level 6;
        gzip_buffers 16 8k;
        gzip_http_version 1.1;
        gzip_types
                # text/html is always compressed by HttpGzipModule
                # Source: https://github.com/google/ngx_brotli#sample-configuration
                application/atom+xml
                application/javascript
                application/json
                application/rss+xml
                application/vnd.ms-fontobject
                application/x-font-opentype
                application/x-font-truetype
                application/x-font-ttf
                application/x-javascript
                application/xhtml+xml
                application/xml
                font/eot
                font/opentype
                font/otf
                font/truetype
                image/svg+xml
                image/vnd.microsoft.icon
                image/x-icon
                image/x-win-bitmap
                text/css
                text/javascript
                text/plain
                text/xml

                # Additional:
                application/xml+rss
                font/ttf
                text/x-component
            ;

        # if your nginx supports brotli you can do same for brotli
        # see: https://github.com/google/ngx_brotli#sample-configuration

.. warning::

    Be sure to also configure your local host-file, if running Sulu locally.

File upload
-----------

By default nginx has a file limit of 2MB when uploading files.
To increase this add the following to your ``nginx.conf``:

.. code-block:: xml

    # ...

    http {
        client_max_body_size 512m;

        # ...
    }

Don't forget to also increase the ``post_max_size`` and ``upload_max_filesize`` in
your ``php.ini``.

.. include:: file-permissions.inc.rst
