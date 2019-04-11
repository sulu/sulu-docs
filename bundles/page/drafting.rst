Drafting
========

The drafting feature of Sulu allows to work on a draft of a page, which will
not be available on the website directly. For this to happen the page has to be
published. This document will explain the technical structure behind this
feature and how to maintain it.

Structure
---------

In general we use `PHPCR`_ to store our unstructured content like pages and
snippets. We use two different PHPCR workspaces, which can be compared to
schemas in relational databases. There is one for all the drafts and one for
the already published content.

If a document is saved using the ``persist`` method from the ``DocumentManager``
it will only be saved in the draft workspace and therefore not being available
on the website. When the ``publish`` method  from the ``DocumentManager`` is
called the content of the passed document will be saved to the live workspace,
and though be available on the website.

Sulu also maintains two different search indexes for pages. There is one for
the published data, named after the scheme
``sulu_page_<webspace>-<locale>-i18n_published`` and one for the drafting data
called ``sulu_page_<webspace>-<locale>-i18n``.

Configuration
-------------

The sessions are configured in the ``sulu_document_manager.sessions`` option.
Sessions can have names, which can be used to reference them in the
``sulu_document_manager.default_session`` and
``sulu_document_manager.live_session``. This configuration will tell the system
where to save the content for the drafts and live documents. See the following
configuration for an example:

.. code-block:: yaml

    sulu_document_manager:
        default_session: default
        live_session: live
        sessions:
            default:
                backend: "%phpcr_backend%"
                workspace: "%phpcr_workspace%"
                username: "%phpcr_user%"
                password: "%phpcr_pass%"
            live:
                backend: "%phpcr_backend%"
                workspace: "%phpcr_workspace%_live"
                username: "%phpcr_user%"
                password: "%phpcr_pass%"

.. note::

    Remember to set the default session differently for both Kernels. The 
    AdminKernel should have the ``default`` session configured as
    ``default_session``, whereby the `WebsiteKernel` should have the
    ``live`` session configured as ``default_session``. So the documents are
    read from the correct workspace for both Kernels.

Session handling
----------------

Most of the commands with the ``doctrine:phpcr:`` take a ``--session``
parameter, which specifies which of the configured sessions should be used. So
if you want to start e.g. the `PHPCR Shell`_:

.. code-block:: bash

    php bin/console doctrine:phpcr:shell --session=live

If you want to start the `PHPCR Shell`_ with the ``default_session`` you can
simply omit the ``--session`` parameter.

Search handling
---------------

As already described the pages are stored in two different search indexes. So
if you want to reindex the content you have to address both of these indexes.
You do so by using two different console commands:

.. code-block:: bash

    php bin/console massive:search:reindex # Reindex the default session
    php bin/websiteconsole massive:search:reindex # Reindex the live session

.. _PHPCR: http://phpcr.github.io/
.. _PHPCR Shell: http://phpcr.readthedocs.io/en/latest/phpcr-shell/
