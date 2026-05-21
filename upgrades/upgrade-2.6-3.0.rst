Upgrading from Sulu 2.6 to 3.0
==============================

Sulu 3.0 ships a number of larger changes — most prominently the migration of all PHPCR-based content
(pages, snippets, articles) into a new Doctrine-based content architecture. Because of this, upgrading
a Sulu project from 2.x to 3.0 is not a pure ``composer update``, but a two-phase process: first you
bring the existing 2.6 project into a clean, fully migrated state, and only then you switch to 3.0 and
run the content migration on top of it.

The `UPGRADE.md file`_ in the ``sulu/sulu`` repository contains the complete list of breaking changes
between 2.x and 3.0. This guide describes the recommended high-level path and the steps that almost
every project has to perform.

.. note::

    Always create a database backup before starting the upgrade. The migration touches both the
    relational database and the PHPCR repository, and the second phase is significantly easier to
    repeat if you can restore the dump after a failed attempt.

Prerequisites
-------------

Before starting the upgrade, make sure the project runs on at least **Symfony 6.4**. Sulu 3.0 no
longer supports older Symfony versions, so projects still on Symfony 5.4 or earlier must be updated
to Symfony 6.4 first. Doing this on the existing Sulu 2.6 codebase keeps the Symfony upgrade
isolated from the Sulu upgrade and avoids mixing two unrelated sources of breaking changes.

Phase 1 — Prepare the project on Sulu 2.6
-----------------------------------------

The goal of this phase is to put the project into a clean state on the latest Sulu 2.6 release with all
migrations applied, so that the actual upgrade to 3.0 starts from a known baseline. **All steps in this
phase are executed on the Sulu 2.6 codebase against the database that will be migrated.**

**1. Update sulu/sulu to the latest 2.6.x version**

Bump the ``sulu/sulu`` version constraint in your ``composer.json`` to the latest ``2.6.x`` release and run:

.. code-block:: bash

    composer update sulu/sulu --with-dependencies

**2. Update all sulu/* bundles to their latest 2.6-compatible version**

Update every other ``sulu/*`` bundle used in the project (for example ``sulu/article-bundle``,
``sulu/headless-bundle``, ``sulu/form-bundle``, ``sulu/automation-bundle``, …) to the latest version
that is compatible with Sulu 2.6. Keeping the ``sulu/article-bundle`` up to date is particularly
important, because the article migration relies on its latest PHPCR migrations having been applied.

**3. Run the PHPCR migrations**

Execute the outstanding PHPCR migrations to make sure the content repository is on the latest
structure that 2.6 expects:

.. code-block:: bash

    php bin/console phpcr:migrations:migrate

**4. Run the PHPCR cleanup command**

Sulu 2.6 ships a cleanup command that removes obsolete properties from the PHPCR repository. Running
it before the upgrade keeps the data set as small and consistent as possible and significantly reduces
the chance of problems during the later content migration:

.. code-block:: bash

    php bin/console sulu:document:phpcr-cleanup

**5. Dump the database**

Once all migrations have run and the cleanup command has finished, create a database dump. This dump
is your fallback if anything goes wrong during Phase 2 — you can restore it and repeat the 3.0 upgrade
without having to redo Phase 1.

Phase 2 — Upgrade the project to Sulu 3.0
-----------------------------------------

With the project on a clean 2.6 state, you can now switch over to Sulu 3.0. We recommend doing this on
a separate branch so the 2.6 version stays available for reference.

**1. Update composer.json to the 3.0 versions**

Update the version constraint of ``sulu/sulu`` and of every ``sulu/*`` sub-bundle to the version that
is compatible with Sulu 3.0, then run:

.. code-block:: bash

    composer update

**2. Compare the sulu/skeleton changes**

Compare the `sulu/skeleton repository`_ between Sulu 2.6 and Sulu 3.0 and apply the configuration
changes that make sense for your project. The relevant differences are mainly in ``config/``,
``.env``, the bundle list and other Symfony/Sulu defaults. The admin JavaScript build itself does
not need to be updated manually — that is handled later by ``sulu:admin:update-build``.

.. note::

    For a convenient view of all changes in the skeleton repository, open
    https://github.com/sulu/skeleton/compare/ and select ``2.6`` as ``base`` and ``3.0`` as
    ``compare``.

**3. Walk through the UPGRADE.md file**

Go through the `UPGRADE.md file`_ in the ``sulu/sulu`` repository from your previous version up to the
target 3.0 release and apply the changes that affect your project. Some of these changes cannot be
automated and must be applied to your project code and configuration manually.

.. note::

    A few entries in ``UPGRADE.md`` reference SQL statements that must be executed manually. Make sure
    to perform those steps as you walk through the file — they are required for the following
    migrations to run successfully.

**4. Run the Sulu 3.0 migrations**

Sulu 3.0 ships its core schema migrations through the Doctrine Migrations Bundle, which is already
pulled in as a dependency of ``sulu/sulu``. Run the migrations that come with Sulu 3.0:

.. code-block:: bash

    php bin/console doctrine:migrations:migrate

**5. Adjust template XML for the new route content type**

The Sulu 3.0 content structure requires every routable template to use the ``route`` content type
on a property named ``url``, with the ``sulu.rlp.part`` tag on the title and the ``sulu.rlp`` tag on
the URL property. See the *Upgrade resourceLocator and route property type* section in ``UPGRADE.md``
for the diff and full details.

**6. Rebuild the admin frontend**

Sulu 3.0 ships updated admin JavaScript dependencies and assets. Rebuild the administration interface
so it matches the new backend:

.. code-block:: bash

    php bin/console sulu:admin:update-build

If you use a custom admin build, refer to the ``sulu/skeleton`` repository for the updated build setup
and run your custom build pipeline accordingly. Note that Sulu 3.0 requires at least ``Node 20`` for
custom admin builds.

**7. Install the PHPCR migration bundle**

The actual migration of pages, snippets and articles from PHPCR into the new Doctrine-based content
storage is provided by a dedicated bundle. Install it:

.. code-block:: bash

    composer require sulu/phpcr-migration-bundle

**8. Run the PHPCR migration in dry-run mode**

Before performing the real migration, execute the command in dry-run mode. Dry-run mode runs through
all content without writing the result back, which is the easiest way to surface exceptions that
would otherwise interrupt the migration mid-way:

.. code-block:: bash

    php bin/console sulu:phpcr-migration:migrate --dry-mode

Fix every error that the dry run reports — typically these are template or configuration mismatches
that need to be adjusted in your project — and re-run the command until it finishes without errors.

**9. Run the PHPCR migration**

Once the dry run completes successfully, run the migration without ``--dry-mode`` to actually transfer
all PHPCR data into the new content architecture:

.. code-block:: bash

    php bin/console sulu:phpcr-migration:migrate

**10. Reindex the search**

Populate the new search index (admin and website) with the migrated Sulu 3.0 data:

.. code-block:: bash

    php bin/console cmsig:seal:reindex --drop

**11. Verify the migration**

Log in to the Sulu admin panel and check that pages, articles, snippets, media and other entities are
present and rendered correctly before continuing.

**12. Remove the PHPCR migration bundle**

Once the migration has been verified, the bundle is no longer needed and can be removed:

.. code-block:: bash

    composer remove sulu/phpcr-migration-bundle

**13. Drop the obsolete PHPCR tables**

The ``phpcr_*`` tables in the database are no longer required by Sulu 3.0. Generate a Doctrine schema
diff to create a migration that removes them:

.. code-block:: bash

    php bin/console doctrine:migrations:diff

Review the generated migration file to confirm it only drops the expected ``phpcr_*`` tables, then
execute it:

.. code-block:: bash

    php bin/console doctrine:migrations:migrate

Common issues after upgrading
-----------------------------

The most frequent breaking changes you may run into after the upgrade are listed below. See the
linked ``UPGRADE.md`` sections for full migration details.

**Container build fails: deprecated smart content parameters**

In ``smart_content`` properties, the ``types`` parameter has been split per provider and the
``structureTypes`` parameter has been renamed:

- For article providers (``articles``, ``articles_page_tree``), replace ``types`` with ``groups``.
- For all other providers (``pages``, ``snippets``), replace ``types`` with ``templateKeys``.
- Replace ``structureTypes`` with ``templateKeys``.

If a deprecated parameter is still used, the container build fails with a message pointing at the
affected template and property. See *Consistent smart content params across article, page and snippet
providers* in ``UPGRADE.md``.

**Articles are missing from the admin or have no template group**

If your project used the ArticleBundle with article types defined under ``sulu_article.types`` in
``config/packages/sulu_article.yaml``, those types have been replaced by template groups. Remove the
``types`` block from the configuration and add a ``<group>`` element to each affected article template
XML file (for example ``<group>blog</group>``). After the migration, the new template groups also need
to be granted to user roles in the Sulu admin interface. See *Migrating from Article Types to Template
Groups* in ``UPGRADE.md`` for the full migration.

**Navigation Twig functions throw "unknown function" errors**

The navigation Twig functions have been renamed with a ``sulu_page_`` prefix (for example
``sulu_navigation_tree`` → ``sulu_page_navigation_tree``, ``sulu_breadcrumb`` →
``sulu_page_breadcrumb``). Their default property set has been reduced to ``title`` and ``url``, and
``nodeType`` has been replaced by ``linkProvider``. See *Navigation Twig functions renamed* and
*Navigation Twig Extension property filtering* in ``UPGRADE.md`` for the full list of renames and
migration examples.

**``sulu_content_load`` no longer exists or "properties" argument is missing**

The ``sulu_content_load`` Twig function has been split into ``sulu_page_load`` and
``sulu_article_load``, and the ``properties`` parameter is now mandatory.
``sulu_snippet_load_by_area`` also takes a mandatory ``properties`` parameter now. See *Content load
Twig functions split and properties now required* in ``UPGRADE.md`` for the new signatures.

.. _sulu/skeleton repository: https://github.com/sulu/skeleton
.. _UPGRADE.md file: https://github.com/sulu/sulu/blob/3.0/UPGRADE.md
