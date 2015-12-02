Security
========

Every page has its own permission tab, which is integrated like described in
:doc:`../../cookbook/securing-your-application`. The settings placed in this tab
has an influence on the UI of Sulu. The UI might also be adapted due to the
permissions defined in Security Contexts (see the documentation at
:doc:`security` for more details).

The form adapts to the permissions by changing its toolbar. The delete option
in the edit dropdown is only available if the user has the delete permission on
this page. The other toolbar items are only available if the user has the
`edit` permission.

The column navigation for the content is a bit more complicated. The entire
tree is always visible, but the available funcationality is changing based on
the permissions of the page. The `delete` permission is tied to the delete
item in the option dropdown. The `edit` permission is required to move and sort
the page in the content tree. For sorting the page the `edit` permission of all
siblings and the parent page is also required.

For copying a page the user has to have the `view` permission on the source
page.

In addition to that the icon appearing on hovering one of the items in the
content tree depends on its permission. If the user is allowed to edit the page
a pencil shows up, in case the user has only ther permission to view the page
an eyes is shown instead. In case the user has no permission at all, there also
will not appear any icon on hovering.

