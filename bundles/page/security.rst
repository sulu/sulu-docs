Security
========

Every page has its own permission tab, which is integrated like described in
:doc:`../../cookbook/securing-your-application`. The permissions for the Sulu
system in this tab has an influence on the UI of Sulu. The UI might also be
adapted due to the permissions defined in Security Contexts.

The form adapts to the permissions by changing its toolbar:

* Deleting the page requires the ``delete`` permission.
* Saving as draft, changing the template and copying the content to another locale require ``edit``.
* Publishing, deleting the draft and setting the page unpublished require ``live``.
* Saving and publishing in one step requires ``edit`` and ``live``.
* In the form of a new page the permissions of the webspace apply: saving requires ``add``, publishing ``live``.
* Available since Sulu 3.1. **Save and request for publish** and cancelling a request require ``edit``. A request is
  part of a workflow where an editor requests publishing and a reviewer approves.
* Available since Sulu 3.1. Approving or rejecting a request requires ``review``. Nobody can approve or reject their
  own request.
* Available since Sulu 3.1. Once a request is approved, ``edit`` is enough to publish it in the review overlay.

The toolbar shows the publishing options for ``live`` alone, but publishing also needs ``edit`` in the API.
The API checks these permissions too, and a call without them answers ``403``:

* Creating a page requires ``add`` on its webspace.
* Publishing, unpublishing and removing a draft require ``edit`` and ``live`` on the page.
* Available since Sulu 3.1. While an approved request for the page is open, publishing requires only ``edit``.

The column navigation for the content is a bit more complicated. The entire
tree is always visible, but the available functionality is changing based on
the permissions of the page. The `delete` permission is tied to the delete
item in the option dropdown. The `edit` permission is required to move and sort
the page in the content tree. For sorting the page the `edit` permission of all
siblings and the parent page is also required.

For copying a page the user has to have the ``edit`` permission on the page and
the ``add`` permission on the page the copy is created under.

In addition to that the icon appearing on hovering one of the items in the
content tree depends on its permission. If the user is allowed to edit the page
a pencil shows up, in case the user has only the permission to view the page
an eyes is shown instead. In case the user has no permission at all, there also
will not appear any icon on hovering.

In addition to the Sulu system the system of the webspace for this page is also
shown on the permission tab, if the webspace has a system configured (see
:doc:`../../book/webspaces` for more information on how to do this). If the
webspace has configured the system with the `permission-check` flag set to
`true`, Sulu will automatically check in this webspace if the current visitor
is allowed to see the current page and only show pages in the navigation and
smart content listings if the visitor has sufficient permissions.

Further Permissions of a Webspace
---------------------------------

Besides the context of the webspace itself, every webspace has contexts for
functions that are not tied to a page:

.. list-table::
    :header-rows: 1

    * - Security context
      - Permissions
      - Description
    * - ``sulu.webspaces.<webspace>.analytics``
      - ``view``, ``add``, ``edit``, ``delete``
      - Manage the analytics of the webspace.
    * - ``sulu.webspaces.<webspace>.cache``
      - ``delete``
      - Clear the website cache of the webspace. Available since Sulu 3.1. In Sulu 3.0 the ``live`` permission on
        ``sulu.webspaces.<webspace>`` clears the cache.
    * - ``sulu.webspaces.<webspace>.custom-urls``
      - ``view``, ``add``, ``edit``, ``delete``
      - Manage the custom URLs of the webspace.
    * - ``sulu.webspaces.<webspace>.snippet-areas``
      - ``view``, ``edit``
      - See and change the snippet areas of the webspace.
