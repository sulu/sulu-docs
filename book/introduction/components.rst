Which components are packed into Sulu?
======================================

The standard installation of Sulu comes with a set of components (called
„modules“) required for the content management process:


Contacts
--------

The Contact module has two main purposes: The first (and in many cases most
important) is managing the users that have access to the administration
backend of the website. The second is organizing the user-data collected
through the website (e.g. newsletter registrations etc.). In more complex
environments this module can also be used to manage community members,
online-shop customers or other contact based data.


Assets
------

The Assets module let you upload and organize any type of documents such as
pictures, text documents (PDF, Word, Excel etc.), videos or audio clips. Once
uploaded an asset can be used in as many webpages as required remaining its
single source in the Assets module. This means if you would like to change a
document that is used in several different webpages you would only have to
replace it once in Assets. Pictures will be automatically transformed to
web-compatible formats and resized to the required formats of the templates
while the original file will be stored as well. All other document types
remain in their original format.


Webspaces
---------

A webspace is the place where the actual website structure and content will be
created. Within a webspace, one single content-structure will be defined, but by
using e.g. multiple languages and sub-domains, an unlimited number of websites
that share the same structure may be available. Furthermore, an unlimited number
of webspaces can be managed in one Sulu installation.

Confused? Maybe this example helps:

ACME Inc. has a website www.acme.com that needs to be published in English,
German and French. The easy way to do this is to let the user choose their
desired language and stay on the same domain displaying the required content
using sub-domains, such as e.g. www.acme.com/de. For the user or a search
engine, this would mean 1 website with 3 languages sharing the same content
structure.

Next, let's assume that ACME Inc. wants to dedicate each language to its
correspondent market by using top-level-domains. This would of course be more
marketing oriented and search engine friendly. The English content would be
published in www.acme.com, the German content in www.acme.de and the French
content in www.acme.fr. Let's go even further and say that each website's design
should be a little different, maybe with a different header color. The user and
the search engine would now have 3 separate websites, each with 1 language and
individual design but all with the same content-structure.

Any of these scenarios can be implemented with Sulu using one single webspace.


Settings
--------

As the title implies this module gives you access to all internal adjustments
of Sulu. One very important section is "Permissions", where you can create user
roles with access rights which then can be applied to a user in the Contacts
module. This gives you complete control over the access rights of your website
administrators. In addition, you can manage meta information such as categories
or tags that are used in other modules.

Now that you know all the components of Sulu, we'll have a closer look at one
of the paradigms we committed ourselves to: Separation of concerns.
