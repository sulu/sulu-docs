Extend Entities
===============

Sulu allows to extend and replace the following internal entities.

* User
* Role
* Contact
* Account
* Category
* Media
* Tag

You can extend all of them in the same way. Therefore we explain it for `User` here.

Create a Entity
---------------

Create your own Entity that extends Sulu `User` class.

.. code-block:: php

    <?php

    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Sulu\Bundle\SecurityBundle\Entity\User as SuluUser;

    /**
     * The following annotations are required for replacing the table of the extended entity:
     *
     * @ORM\Table(name="se_users")
     * @ORM\Entity
     */
    class User extends SuluUser
    {
        /**
         * @var string
         *
         * @ORM\Column(name="myProperty", type="string", length=255, nullable = true)
         */
        private $myProperty;

        /**
         * Set myProperty
         *
         * @param string $myProperty
         * @return User
         */
        public function setMyProperty($myProperty)
        {
            $this->myProperty = $myProperty;

            return $this;
        }

        /**
         * Get myProperty
         *
         * @return string
         */
        public function getMyProperty()
        {
            return $this->myProperty;
        }
    }

.. warning::

    Your Entity can have own properties, but they should have at least default values.
    Otherwise the normal features of Sulu could crash (like the
    `sulu:security:user:create` command).

.. warning::

    The `@ORM\\Table` annotation on your entity must match the table of the extended entity.
    Otherwise, doctrine might run into errors when querying data of the entity.

Configuration
-------------

Configure Sulu to use your own entity instead in the respective file in the
``config/packages/*`` folder. If the file does not exist, you need to create it.

For the `User` entity (`se_users`):

.. code-block:: yaml

    # config/packages/sulu_security.yaml
    sulu_security:
        objects:
            user:
                model: App\Entity\User
                repository: Sulu\Bundle\SecurityBundle\Entity\UserRepository

.. note::

   Symfony caches the user object in the session, clearing the sessions is so sometimes required
   when running into ``php.CRITICAL: Uncaught Error: Failed opening required /var/project/var/cache/website/prod/doctrine/orm/Proxies/__CG__SuluBundleSecurityBundleEntityUser.php``

For the `Role` entity (`se_roles`):

.. code-block:: yaml

    # config/packages/sulu_security.yaml
    sulu_security:
        objects:
            role:
                model:                Sulu\Bundle\SecurityBundle\Entity\Role
                repository:           Sulu\Bundle\SecurityBundle\Entity\RoleRepository

For the `Contact` entity (`co_contacts`):

.. code-block:: yaml

    # config/packages/sulu_contact.yaml
    sulu_contact:
        objects:
            contact:
                model:                Sulu\Bundle\ContactBundle\Entity\Contact
                repository:           Sulu\Bundle\ContactBundle\Entity\ContactRepository

For the `Account` entity (`co_accounts`):

.. code-block:: yaml

    # config/packages/sulu_contact.yaml
    sulu_contact:
        objects:
            account:
                model:                Sulu\Bundle\ContactBundle\Entity\Account
                repository:           Sulu\Bundle\ContactBundle\Entity\AccountRepository

For the `Category` entity (`ca_categories`):

.. code-block:: yaml

    # config/packages/sulu_category.yaml
    sulu_category:
        objects:
            category:
                model:                Sulu\Bundle\CategoryBundle\Entity\Category
                repository:           Sulu\Bundle\CategoryBundle\Entity\CategoryRepository
            category_meta:
                model:                Sulu\Bundle\CategoryBundle\Entity\CategoryMeta
                repository:           Sulu\Bundle\CategoryBundle\Entity\CategoryMetaRepository
            category_translation:
                model:                Sulu\Bundle\CategoryBundle\Entity\CategoryTranslation
                repository:           Sulu\Bundle\CategoryBundle\Entity\CategoryTranslationRepository
            keyword:
                model:                Sulu\Bundle\CategoryBundle\Entity\Keyword
                repository:           Sulu\Bundle\CategoryBundle\Entity\KeywordRepository

For the `Media` entity (`me_media`):

.. code-block:: yaml

    # config/packages/sulu_media.yaml
    sulu_media:
        objects:
            media:
                model:                Sulu\Bundle\MediaBundle\Entity\Media
                repository:           Sulu\Bundle\MediaBundle\Entity\MediaRepository

For the `Tag` entity (`ta_tags`):

.. code-block:: yaml

    # config/packages/sulu_tag.yaml
    sulu_tag:
        objects:
            tag:
                model:                Sulu\Bundle\TagBundle\Entity\Tag
                repository:           Sulu\Bundle\TagBundle\Entity\TagRepository

After changing the configuration of your project, use the following command to clear the
Symfony cache:

.. code-block:: bash

    $ php bin/adminconsole cache:clear

.. warning::

    If you override entities in an existing project, you need to migrate the existing data
    to avoid data loss.
