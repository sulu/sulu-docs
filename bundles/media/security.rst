Security
========

Separate permissions can be applied to every collection. These permissions also
apply to the assets contained in the collection.

So in the Sulu system (representing Sulu's administration interface) the `view`
permission is required to see the collection itself in the navigation and its
assets in any available view in the system. The `add` permission is needed to
upload new assets and add new subcollections to a collection. With the `edit`
permission the user is allowed to edit the title and other attributes of the
collections and its containing assets, and finally the `delete` permission is
required to delete assets and entire collections.

The permissions for collections will also show the systems for each webspace if
configured with the `permission-check` flag set to `true` (see
:doc:`../../book/webspaces` for more information on how to do this). Then Sulu
will only show the media on this website (no matter if it is displayed via a
smart content or media selection) if the current visitor has the `view`
permission for this media.
