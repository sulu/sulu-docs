Glossary
========

A glossay is:

     A list of terms in a particular domain of knowledge with their definitions.

This page aims to list all of the terminology used within Sulu both as a
reference and as a guide to use when naming things in the code-base.

Component
    Of a Structure -- a named set of Properties. Used by blocks.

Content ??
    In Sulu technical documentation, content refers to the data contained
    within a Document which is mapped to *content types* mapped by the
    *structure*.

Document
    Documents are the domain representation of nodes from the PHPCR content
    repository. For example "PageDocument", or "SnippetDocument"

Document type
    The short name for a class of document, for example "page" the name of the
    PageDocument class.

Front
    Of something relating to the website side of the system (as opposed to the
    admin side). Specfically relating to content, e.g. FrontView.

Locale
    Represents a linguistic region, for example `de`, `de_at`, `en` or `en_us`.

Localized
    Of a Property - the state of being localized, or capable of being translated.

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

Property == ??
    This term refers to the items in a Structure which relate to the
    configuration of Property Types

Property Type == Content Typek
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

Workflow Stage
    The stage of the workflow, for example "published" and "test" are stages.

Snippet
    Snippets are like pages except that they are not accessible directly with
    URLs. Snippets are typically aggregated within pages.

Structure
    Structures are a key concept in Sulu. Structures represent the purposeful
    aggregation of content types into a single form. Documents provide the
    data for these forms.

Structure type
    The name of a given structure, e.g. ``overview``, ``hotel`` or
    ``article``.

Webspace
    In Sulu a webspace encapsulates all of the data of one or more domains
    which use the same data.

Webspace Document
    The document at the root of the webspace tree -- the homepage.
