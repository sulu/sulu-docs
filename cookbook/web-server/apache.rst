Apache
======

The Apache HTTP Server is the world's most used web server (according to
Wikipedia). It is open source, mature and reliable.

To serve a Sulu website with Apache, you need to adapt your `hosts` file, create
a virtual host configuration file and restart your server.

Host Name Configuration
-----------------------

Add the domain of your site to the `hosts` file. Depending on your operating
system, this file can be found in different places:

* Unix: ``/etc/hosts``
* Windows: ``%SystemRoot%\System32\drivers\etc\hosts``

On a development machine, we could use the domain `sulu.lo` ("lo" stands for
"local"). Add that domain to the end of the `hosts` file:

.. code-block:: text

    # ...

    127.0.0.1  sulu.lo

When you type the URL http://sulu.lo in your browser, the browser will now
load the page from your computer.

.. note::

    You may need to restart your browser after changing the `hosts` file.

But before it works, we need to tell Apache what to do when that URL is loaded.

Virtual Host Configuration
--------------------------

Let's add the Apache configuration file for the `sulu.lo` domain.

.. code-block:: apache

  <VirtualHost *:80>
      DocumentRoot "/var/www/sulu.lo/public"
      ServerName sulu.lo
      <Directory "/var/www/sulu.lo/public">
          Options Indexes FollowSymLinks
          AllowOverride All
          Require all granted
          # For Apache 2.2 instead of 'Require all granted' the following is needed:
          # Order allow,deny
          # Allow from all

          <IfModule mod_expires.c>
              ExpiresActive On
              ExpiresByType image/gif "access plus 1 year"
              ExpiresByType image/png "access plus 1 year"
              ExpiresByType image/svg+xml "access plus 1 year"
              ExpiresByType image/svg "access plus 1 year"
              ExpiresByType image/jpeg "access plus 1 year"
              ExpiresByType image/jpg "access plus 1 year"
              ExpiresByType image/webp "access plus 1 year"
              ExpiresByType image/x-icon "access plus 1 year"
              ExpiresByType image/vnd.microsoft.icon "access plus 1 year"
              ExpiresByType application/javascript "access plus 1 year"
              ExpiresByType text/javascript "access plus 1 year"
              ExpiresByType text/css "access plus 1 year"
              ExpiresByType font/woff2 "access plus 1 year"
              ExpiresByType font/woff "access plus 1 year"
              ExpiresByType font/eot "access plus 1 year"
              ExpiresByType font/ttf "access plus 1 year"
              ExpiresByType video/mp4 "access plus 1 year"
          </IfModule>

          <IfModule mod_headers.c>
              <filesMatch ".(ico|css|js|gif|webp|jpe?g|png|svg|woff|woff2|eot|ttf|mp4)$">
                  Header set Cache-Control "public, max-age=31536000, immutable"
              </filesMatch>

              # recommended security headers
              Header set X-Content-Type-Options "nosniff"
              Header set X-Frame-Options "sameorigin"
              Header set X-XSS-Protection "1; mode=block"
          </IfModule>

          <IfModule mod_deflate.c>
              AddOutputFilterByType DEFLATE text/html
              AddOutputFilterByType DEFLATE application/atom+xml
              AddOutputFilterByType DEFLATE application/javascript
              AddOutputFilterByType DEFLATE application/json
              AddOutputFilterByType DEFLATE application/rss+xml
              AddOutputFilterByType DEFLATE application/vnd.ms-fontobject
              AddOutputFilterByType DEFLATE application/x-font-opentype
              AddOutputFilterByType DEFLATE application/x-font-truetype
              AddOutputFilterByType DEFLATE application/x-font-ttf
              AddOutputFilterByType DEFLATE application/x-javascript
              AddOutputFilterByType DEFLATE application/xhtml+xml
              AddOutputFilterByType DEFLATE application/xml
              AddOutputFilterByType DEFLATE font/eot
              AddOutputFilterByType DEFLATE font/opentype
              AddOutputFilterByType DEFLATE font/otf
              AddOutputFilterByType DEFLATE font/truetype
              AddOutputFilterByType DEFLATE image/svg+xml
              AddOutputFilterByType DEFLATE image/vnd.microsoft.icon
              AddOutputFilterByType DEFLATE image/x-icon
              AddOutputFilterByType DEFLATE image/x-win-bitmap
              AddOutputFilterByType DEFLATE text/css
              AddOutputFilterByType DEFLATE text/javascript
              AddOutputFilterByType DEFLATE text/plain
              AddOutputFilterByType DEFLATE text/xml
          </IfModule>
      </Directory>
  </VirtualHost>

.. note::
    It is a good practice to create a virtual host for each of your Sulu projects.
    They are separated by domain and you'll got full control on what you expose.

MAMP Pro
--------

In general you should configure your vHost like the `Apache`_ paragraph above describes it.

.. figure:: ../../img/sulu-mamp-pro-screen.jpg
	:align: center

.. include:: file-permissions.inc.rst
