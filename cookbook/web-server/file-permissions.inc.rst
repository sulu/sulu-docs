File Permissions
----------------

Finally, we need to fix the permissions of our project so that the web server
is able to read and write them.

This command is different for sulu-standard and sulu-minimal.

sulu-standard
~~~~~~~~~~~~~

Run the following commands on Linux:

.. code-block:: bash

    HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
    sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs uploads uploads/* web/uploads web/uploads/* app/data
    sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs uploads uploads/* web/uploads web/uploads/* app/data

Or these commands for Mac OSX:

.. code-block:: bash

    HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
    sudo chmod +a "$HTTPDUSER allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs uploads uploads/* web/uploads web/uploads/* app/data
    sudo chmod +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs uploads uploads/* web/uploads web/uploads/* app/data

Or these commands for Windows (with IIS web server):

.. code-block:: powershell

    $rule = New-Object System.Security.AccessControl.FileSystemAccessRule -ArgumentList @("IUSR","FullControl","ObjectInherit, ContainerInherit","None","Allow")
    $folders = "app\cache", "app\logs", "app\data", "uploads", "uploads\*", "web\uploads", "web\uploads\*"
    foreach ($f in $folders) { $acl = Get-Acl $f; $acl.SetAccessRule($rule); Set-Acl $f $acl; }

sulu-minimal
~~~~~~~~~~~~

Run the following commands on Linux:

.. code-block:: bash

    HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
    sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var/cache var/logs var/uploads var/uploads/* web/uploads web/uploads/* var/indexes var/sessions
    sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var/cache var/logs var/uploads var/uploads/* web/uploads web/uploads/* var/indexes var/sessions


Or these commands for Mac OSX:

.. code-block:: bash

    HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
    sudo chmod +a "$HTTPDUSER allow delete,write,append,file_inherit,directory_inherit" var/cache var/logs var/uploads var/uploads/* web/uploads web/uploads/* var/indexes var/sessions
    sudo chmod +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" var/cache var/logs var/uploads var/uploads/* web/uploads web/uploads/* var/indexes var/sessions


Or these commands for Windows (with IIS web server):

.. code-block:: powershell

    $rule = New-Object System.Security.AccessControl.FileSystemAccessRule -ArgumentList @("IUSR","FullControl","ObjectInherit, ContainerInherit","None","Allow")
    $folders = "var\cache", "var\logs", "var\indexes", "var\sessions", "var\uploads", "var\uploads\*", "web\uploads", "web\uploads\*"
    foreach ($f in $folders) { $acl = Get-Acl $f; $acl.SetAccessRule($rule); Set-Acl $f $acl; }
