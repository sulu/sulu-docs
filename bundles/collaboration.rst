CollaborationBundle
===================

This bundle activates the collaboration feature of Sulu. It shows a label, when
other content managers are currently editing the same record. AJAX polling has
been implemented, in order to notify current editors when other editors join
editing the page.

The bundle saves this data in the cache and the records are identified by a
type, which is a simple string uniquely describing the type of record (e.g.
``page`` or ``contact``) and the identifier of the record.

The feature automatically works for all forms using the standard forms view of
Sulu.
