Configure your local Webserver
==============================

You need to configure a virtual host for your webserver in order to run a clean
Sulu instance. If you've already done so, skip this section.

.. note::
    It is a good decision to create a virtual host for each of your Sulu projects.
    They are separated by domain and you'll got full control on what you expose.

Here is an example for Apache and Nginx.

Apache 2.2
----------

In your virtual hosts file you should configure something like:

.. code-block:: apache

  <VirtualHost *:80>
      DocumentRoot "/var/www/sulu.lo/web"
      ServerName sulu.lo
      <Directory "/var/www/sulu.lo/web">
          Options Indexes FollowSymLinks
          AllowOverride All
          Order allow,deny
          Allow from all
          SetEnv SYMFONY_ENV dev
          
          <IfModule mod_expires.c>
              ExpiresActive On
              ExpiresDefault "access plus 1 month"
              ExpiresByType image/gif "access plus 1 month"
              ExpiresByType image/png "access plus 1 month"
              ExpiresByType image/jpeg "access plus 1 month"
              ExpiresByType image/jpg "access plus 1 month"
              ExpiresByType text/javascript "access plus 1 month"
              ExpiresByType text/css "access plus 1 month"
          </IfModule>
  
          <IfModule mod_deflate.c>
              SetOutputFilter DEFLATE
              SetEnvIfNoCase Request_URI \.(?:gif|jpe?g|png)$ no-gzip dont-vary
              SetEnvIfNoCase Request_URI \.(?:exe|t?gz|zip|bz2|sit|rar)$ no-gzip dont-vary
              SetEnvIfNoCase Request_URI \.pdf$ no-gzip dont-vary
  
              BrowserMatch ^Mozilla/4 gzip-only-text/html
              BrowserMatch ^Mozilla/4\.0[678] no-gzip
              BrowserMatch \bMSIE !no-gzip !gzip-only-text/html
          </IfModule>
      </Directory>
  </VirtualHost>

Nginx
-----

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
          fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
          fastcgi_param  SYMFONY_ENV dev;
          fastcgi_param  HTTPS off;
      }
  }

.. warning::
    Be sure to also configure your local host-file, if running Sulu locally.

MAMP Pro
--------

In general you should configure your vHost like the `Apache 2.2`_ paragraph above describes it.

If you want to enable the dev-environment (*including the debug toolbar*) you have to be sure that the
vHost environment variable (``SetEnv SYMFONY_ENV dev``) is set properly
and you configured a DEV-domain within your  `webspace.xml <setup.rst#webspaces>`_.

.. figure:: ../../img/sulu-mamp-pro-screen.jpg
	:align: center

Now, your Sulu instance is ready.
