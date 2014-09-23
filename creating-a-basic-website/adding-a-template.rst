Adding a template
=================
In Sulu a template consists of multiple content types, whereby a content type
describes the way the data is stored in the database and how to enter them in
the administration interface. Pages in the Sulu content section will be based
on one of these templates.

On this page there are the available content types described, how to define
these values in our template configuration file, what you should consider when
creating the HTML templates, and finally how to connect the data from Sulu to
the HTML template.

Available content types
-----------------------
The following list shows the content types delivered by a standard sulu
installation. The first column shows the key, which acts as an unique
identifier. The second one describeds the appearance in the administration
interface, and the last one how the content is returned to the HTML template.

+----------------------+---------------------------------------------+-----------------------------------------+
| Key                  | Appearance                                  | Value                                   |
+======================+=============================================+=========================================+
| text_line            | simple text line                            | text                                    |
+----------------------+---------------------------------------------+-----------------------------------------+
| text_area            | text area                                   | text                                    |
+----------------------+---------------------------------------------+-----------------------------------------+
| text_editor          | text editor with formatting capabalities    | formatted text                          |
+----------------------+---------------------------------------------+-----------------------------------------+
| color                | color picker                                | text                                    |
+----------------------+---------------------------------------------+-----------------------------------------+
| date                 | date picker                                 | text                                    |
+----------------------+---------------------------------------------+-----------------------------------------+
| time                 | text line with a time validation            | text                                    |
+----------------------+---------------------------------------------+-----------------------------------------+
| url                  | text line with an URL validation            | text                                    |
+----------------------+---------------------------------------------+-----------------------------------------+
| email                | text line with an email validation          | text                                    |
+----------------------+---------------------------------------------+-----------------------------------------+
| password             | password field                              | text                                    |
+----------------------+---------------------------------------------+-----------------------------------------+
| phone                | text line for a phone number                | text                                    |
+----------------------+---------------------------------------------+-----------------------------------------+
| internal_links       | widget for adding links to other pages      | resolved pages as defined in parameters |
+----------------------+---------------------------------------------+-----------------------------------------+
| single_internal_link | widget for selecting a single internal link | resolved page as defined in parameters  |
+----------------------+---------------------------------------------+-----------------------------------------+
| smart_content        | widget for configuring smart contents, a    | resolved pages as defined in parameters |
|                      | content type for aggregating multiple pages |                                         |
+----------------------+---------------------------------------------+-----------------------------------------+
| resource_locator     | widget for entering the URL for the page    | text                                    |
+----------------------+---------------------------------------------+-----------------------------------------+
| tag_list             | autocomplete field for entering and adding  | array of texts                          |
|                      | tags                                        |                                         |
+----------------------+---------------------------------------------+-----------------------------------------+
| media_selection      | widget for adding media (images, documents) | array containing another array with     |
|                      | to the page                                 | urls for every format                   |
+----------------------+---------------------------------------------+-----------------------------------------+

Add a template definition
-------------------------

Build the HTML template
-----------------------

Create the data connection
--------------------------

