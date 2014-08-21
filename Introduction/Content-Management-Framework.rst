============
Content Management Framework
============

Introduction
============
Let’s talk about the difference between a „Content Management Framework“ and a „Content Management System“ as we understand it first. This will prevent some irritations later.

A „Content Management Framework“ (or CMF as cool and lazy people call it) is a set of components that can be used to build a data administration environment customized to the individual needs of the project or client and used for data driven systems, web-apps or large scale web platforms. A „Content Management System“ (or CMS as you might have guessed) is a more or less standardized software with a fixed set of functionalities mainly focused on the management of websites.

Sulu can be both, a CMF or a CMS, but in this documentation we will focus exclusively on the usage as a Content Management System.
The basics
Sulu was created to manage the content of websites and fully support the 4-step „Content Life Cycle“ (http://en.wikipedia.org/wiki/Web_content_lifecycle):

1. Content creation/collection
2. Revision/approval
3. Publishing
4. Archiving
 
The main publishing format is normally HTML but others such as e.g. XML can be easily applied. Sulu was designed to create webpages with high performance in the current state of the internet. This means a high focus on web standards (as described in the W3C standards http://www.w3.org/standards/) and search engine optimization. Furthermore it supports multi-language websites and multi-site platforms with shareable content.

Although Sulu focuses on more complex websites it still provides an UI that is easy to understand, fast to learn and enjoyable to use. The software runs on modern browsers without the need of any plugins and will work on a standard state-of-the-art PC or tablet.

Standard installation components
--------------------------------
The standard installation of Sulu comes with a set of components (called „modules“) required for the content management process:
Contacts 
The Contact module as two main purposes: The first (and in many cases most important) is managing the users that have access to the administration backend of the website. The second is organising the user-data collected through the website (e.g. newsletter registrations etc.). In more complex environments this module can also be used to manage community members, online-shop customers or other contact based data.

Assets
The Assets module let you upload and organize any type of documents such as pictures, text documents (PDF, Word, Excel etc.), videos or audio clips. Once uploaded an asset can be used in as many webpages as required remaining its single source in the Assets module. This means if you would like to change a document that it used in serval different webpages you would only have to replace it once in Assets. Pictures will be automatically transformed to web-compatible formats and resized to the required formats of the templates while the original file will be stored as well. All other document types remain in their original format.
 Webspaces
A Webspace is the place where the actual website structure and content will be created. Within a Webspace one single content-structure can be created but by using e.g. multiple languages and sub-domains an unlimited number of websites that share the same structure can be created. Furthermore an unlimited number of webspaces can be managed in one Sulu installation. 

Confused? Maybe this example helps: 

ACME Inc. has a website www.acme.com that needs to be published in English, German and French. The easy way to do this is to let the user choose their desired language and stay on the same domain displaying the required content using sub-domains such as e.g. www.acme.com/de. For the user or a search engine this would mean 1 website with 3 languages sharing the same content structure.

Now lets assume that ACME Inc. wants to dedicate each language to its correspondent market by using top-level-domains. This would of course be more marketing oriented and search engine friendly. The English content would be published in www.acme.com, the German in www.acme.de and and the French in www.acme.fr. Let’s go even further and say each website`s design should be a little different, maybe with a different header color. The user and the search engine would now have 3 separate websites, each with 1 language and individual design but all with the same content-structure.

Any of this scenarios can be created with Sulu using one Webspace.

Got it? Great! 
(Don’t worry if not, we go a lot deeper into this later.)
Settings
As the title implies this module gives you access to all internal adjustments of Sulu. One very important section is Permissions where you can create user roles with access right which then can be applied to a user in the Contacts module. This gives you complete control over the access rights of your website administrators. In addition you can manage meta information such as categories or tags that are used in other modules.
Backend VS Templates
As in any CMS Sulu completely separates content from design and allows template based content rendering. But due to the fact that usability, web standards and SEO are of such great importance the templates take a big role in the creation of a Sulu based web platform.

To ensure that the templates make use of all the possibilities that the CMS delivers Sulu provides a set of functionalities and development guidelines. Many of these can be found in this documentation and we strongly recommend to carefully read through and use them as much as possible. By doing so your content will be much easier to mange, the performance of your system will be optimized and your website will be more accessible for users,  search engines or third party applications.
