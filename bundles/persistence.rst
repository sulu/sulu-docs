PersistenceBundle
=================

The Sulu Persistence bundle provides extensions for managing Doctrine entities and their repositories.

Column lengths
--------------

The columns for webspace keys, slugs, template keys and the names of navigation contexts
and additional webspaces were widened, for example the webspace key from 31 to 64 characters. The new migrations
apply the change:

.. code-block:: bash

    php bin/console doctrine:migrations:migrate

If the database limits index keys to 767 bytes (MySQL or MariaDB without ``innodb_large_prefix``), the widened columns
do not fit the unique index of ``ro_routes``. Set ``legacy_length`` to keep the previous lengths. The migrations are
skipped and the entity mapping keeps 31 characters for the keys and names and 144 for the slug (208 on PostgreSQL), so
set it before running the migrations:

.. code-block:: yaml

    # config/packages/sulu_persistence.yaml
    sulu_persistence:
        legacy_length: true

The skipped migrations stay pending and widen the columns once the option is removed. Keep webspace keys, navigation
context keys and template keys at 31 characters or less, the XML schemas accept 64.
