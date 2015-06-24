Glossary
========

A glossay is:

     A list of terms in a particular domain of knowledge with their definitions.

This page aims to list all of the terminology used within Sulu both as a
reference and as a guide to use when naming things in the code-base.

Component
    Of a Structure -- a named set of Properties. Used by blocks. ??

Document
    Documents are the domain representation of nodes from the PHPCR content
    repository. For example "PageDocument", or "SnippetDocument".

    The namespace used within components/bundles for all things relating to document
    the document manager component.

Document type
    The short name for a class of document, for example "page" the name of the
    PageDocument class.

Locale
    Represents a linguistic region, for example `de`, `de_at`, `en` or `en_us`.

Localized
    Of a Property - the state of being localized, or capable of being translated.

Metadata
    Literalaly data about data. Typically a data structure with information
    such as field mappings which should be applied to a different data
    structure.

    In the context of Sulu this applied to Structures, Properties and Documents.

Non-localized
    Of a Property - the state of not being localized, not capable of being
    translated.

Path
    Always refers to the path of an object within the content repository,
    for example ``/cmf/sulu_io/contents/animals/dog`` is a path.

Page
    A page is basic type of document. Pages are accessible directly with URLs and
    they represent pages of your website.

Parameter
    In relation to Property and Structure items; a configuration parameter
    which relates to the configuration of the content type.

Prefix
    The former part of a web facing URL which is defined by the portal, it is
    followed by the resource locator. The prefix may include the locale.

Property
    This term refers to the items in a Structure.
    
Property Type
    Property types are the way Sulu represents different types of "content". For
    example, `email`, `text` and `smart_content` are three examples of Sulu
    Property Types

Resource locator
    The later part of a web facing URL belonging to some document, excluding
    the host and prefix segment. For example ``/articles/foo`` is a resource locator,
    however ``/de/articles/foo`` and ``http://example.com/articles/foo`` are not. The
    resource locator will never include the locale.

Segment
    As applying to URLs and Paths - a section of a path or URL, preseumably
    delimited by `/`.

Shadow
    Of a document. A localized document can specify that it should be loaded
    in a different locale. The target locale is called the "shadow" locale.

Workflow Stage
    The stage of the workflow, for example "published" and "test" are stages.

Snippet
    Snippets are like pages except that they are not accessible directly with
    URLs. Snippets are typically aggregated within pages.

Structure
    Structures represent dynamic content in Sulu. A structure is a collection
    of Properties.

Structure type
    The name of a given structure, e.g. ``overview``, ``hotel`` or
    ``article``.

Webspace
    In Sulu a webspace encapsulates all of the data of one or more domains
    which use the same data.

Webspace Document
    The document at the root of the webspace tree -- the homepage.
