Webspace Export
===============

A whole webspace a specific site can be exported in the following formats.

======== ========= ============ 
 Format   Version  Key       
======== ========= ============
 XLIFF    1.2       1.2.xliff  
======== ========= ============


Parameters
------------

Export Command: ``sulu:webspace:export``

================ ================= ==================== ==================== 
 Name             Short             Values               required
================ ================= ==================== ==================== 
 webspace         w                 webspacekey          true
 locale           l                 locale               true
 format           f                 format key           false
 uuid             u                 page uuid            false
================ ================= ==================== ==================== 
Webspace Export
===============

A whole webspace a specific site can be exported in the following formats.

======== ========= ============ 
 Format   Version  Key       
======== ========= ============
 XLIFF    1.2       1.2.xliff  
======== ========= ============


Parameters
------------

Export Command: ``sulu:webspace:export``

================ ================= ==================== ==================== 
 Name             Short             Values               required
================ ================= ==================== ==================== 
 webspace         w                 webspacekey          true
 locale           l                 locale               true
 format           f                 format key           false
 uuid             u                 page uuid            false
================ ================= ==================== ==================== 

.. code-block:: bash

    app/console sulu:webspace:export -w getzner -l de -f 1.2.xliff test.xliff -u 6f7b92c1-81a3-424d-97a6-95728f217fa1
