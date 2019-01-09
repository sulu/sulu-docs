File Permissions
----------------

Finally, we need to fix the permissions of our project so that the web server
is able to read and write them.

Linux
~~~~~

Run the following commands on Linux:

.. code-block:: bash

    HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
    sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var public/uploads
    sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var public/uploads

Mac OSX
~~~~~~~

Or these commands for Mac OSX:

.. code-block:: bash

    HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
    sudo chmod +a "$HTTPDUSER allow delete,write,append,file_inherit,directory_inherit" var public/uploads
    sudo chmod +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" var public/uploads


Windows
~~~~~~~

Or these commands for Windows (with IIS web server):

.. code-block:: powershell

    $rule = New-Object System.Security.AccessControl.FileSystemAccessRule -ArgumentList @("IUSR","FullControl","ObjectInherit, ContainerInherit","None","Allow")
    $folders = "var", "public\uploads"
    foreach ($f in $folders) { $acl = Get-Acl $f; $acl.SetAccessRule($rule); Set-Acl $f $acl; }
