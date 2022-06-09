Migrate existing content data on template change
================================================

Sulu uses `PHPCR`_ as a persistence layer for the data of the pages and snippets of an application.
When a page or a snippet is loaded from PHPCR, Sulu will use its template to resolve the existing data.
Because of this, if you change the name of a property in your template and want to keep the existing data
for that property, you need to migrate the data inside of PHPCR to match the new property name.

To preferred way for migrate existing data is creating a migration script using the
`dantleech/phpcr-migrations-bundle`_ package. Inside of such a migration, you are able to iterate
over all properties of existing pages and snippets and modify them to your needs.

If you want to use the bundle in your project, you need to configure a path that is scanned for migrations
inside of your project by adding a ``config/packages/phpcr_migrations.yaml`` file:

.. code-block:: yaml

    phpcr_migrations:
        paths:
            - '%kernel.project_dir%/phpcr-migrations'

Before creating a migration, it is helpful to inspect the existing properties of a page inside of PHPCR
using the interactive shell of the `phpcr/phpcr-shell`_ package that can be started via
``bin/console doctrine:phpcr:shell``.

The following example migration renames the ``oldPropertyName`` property into ``newPropertyName`` for
all existing pages that use the ``my-template-key`` template. It must be stored in the configured ``phpcr-migrations``
directory of your project and is executed when running ``bin/console phpcr:migrations:migrate``.

.. code-block:: php

    <?php

    namespace App;

    use Jackalope\Query\Row;
    use PHPCR\Migrations\VersionInterface;
    use PHPCR\SessionInterface;
    use Sulu\Component\Localization\Localization;
    use Symfony\Component\DependencyInjection\ContainerAwareInterface;
    use Symfony\Component\DependencyInjection\ContainerAwareTrait;

    class Version202206091200 implements VersionInterface, ContainerAwareInterface
    {
        use ContainerAwareTrait;

        public function up(SessionInterface $session)
        {
            $liveSession = $this->container->get('sulu_document_manager.live_session');
            $localizations = $this->container->get('sulu_core.webspace.webspace_manager')->getAllLocalizations();

            /** @var Localization $localization */
            foreach ($localizations as $localization) {

                $this->upgrade($session,$localization);
                $this->upgrade($liveSession,$localization);

            }

            $session->save();
            $liveSession->save();
        }

        public function down(SessionInterface $session)
        {
            $liveSession = $this->container->get('sulu_document_manager.live_session');
            $localizations = $this->container->get('sulu_core.webspace.webspace_manager')->getAllLocalizations();

            /** @var Localization $localization */
            foreach ($localizations as $localization) {
                $this->downgrade($session,$localization);
                $this->downgrade($liveSession,$localization);
            }

            $session->save();
            $liveSession->save();
        }

        /**
         * Upgrade all nodes in given session.
         */
        private function upgrade(SessionInterface $session, Localization $localization)
        {
            /** @var Row $row */
            foreach ($this->getRowsToMigrate($session, $localization) as $row) {
                $node = $row->getNode();
                $localizedOldPropertyName = \sprintf('i18n:%s-oldPropertyName', $localization->getLocale());
                $localizedNewPropertyName = \sprintf('i18n:%s-newPropertyName', $localization->getLocale());

                if ($node->hasProperty($localizedOldPropertyName)) {
                    $node->setProperty($localizedNewPropertyName, $node->getPropertyValue($localizedOldPropertyName));
                    $node->setProperty($localizedOldPropertyName, null);
                }
            }
        }

        /**
         * Downgrades all nodes in given session.
         */
        private function downgrade(SessionInterface $session, Localization $localization)
        {
            /** @var Row $row */
            foreach ($this->getRowsToMigrate($session, $localization) as $row) {
                $node = $row->getNode();
                $localizedOldPropertyName = \sprintf('i18n:%s-oldPropertyName', $localization->getLocale());
                $localizedNewPropertyName = \sprintf('i18n:%s-newPropertyName', $localization->getLocale());

                if ($node->hasProperty($localizedNewPropertyName)) {
                    $node->setProperty($localizedOldPropertyName, $node->getPropertyValue($localizedNewPropertyName));
                    $node->setProperty($localizedNewPropertyName, null);
                }
            }
        }

        /**
        * Creates a generator that generates all rows that have to be migrated.
        *
        * @return \Generator
        */
        private function getRowsToMigrate(SessionInterface $session, Localization $localization)
        {
            $queryManager = $session->getWorkspace()->getQueryManager();
            
            $pageCondition = '([jcr:mixinTypes] = "sulu:page" OR [jcr:mixinTypes] = "sulu:home")';
            $templateCondition = \sprintf('([i18n:%s-template] = "my-template-key")', $localization->getLocale());

            $query = 'SELECT * FROM [nt:unstructured] WHERE (' . $templateCondition . 'AND' . $pageCondition . ')';
            yield from $queryManager->createQuery($query, 'JCR-SQL2')->execute();
        }
    }

.. _PHPCR: http://phpcr.github.io/
.. _dantleech/phpcr-migrations-bundle: https://github.com/dantleech/phpcr-migrations-bundle
.. _phpcr/phpcr-shell: https://github.com/phpcr/phpcr-shell
