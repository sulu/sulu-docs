Extend Entities
===============

Sulu has a very easy way to extend and replace the internal entities. This feature is not
implemented for each entity but it will be implemented for all soon.

These entities are ready to extend:

* User
* Role
* Contact
* Media

You can extend all of them in the same way. Therefor we explain it for `User` here.

Create a Entity
---------------

Create your own Entity for example in the `ClientWebsiteBundle`. You can use the
`doctrine:generate:entity` command for that. Extend the generated Entity with the
Sulu `User` class.

.. code-block:: php

    <?php

    namespace Client\Bundle\WebsiteBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Sulu\Bundle\SecurityBundle\Entity\User as SuluUser;

    /**
     * Following annotations are required and should not be changed:
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

    The `@ORM\\Table` annotation need to match the exist table else you will get strange
    errors when query data from it.

Configuration
-------------

You can specify your new Entity and if it exists your Repository in the `sulu_security`
configuration section in the file app/config/config.yml.

For the `User` entity (`se_users`):

.. code-block:: yaml

    sulu_security:
        objects:
            user:
                model: Client\Bundle\WebsiteBundle\Entity\User
                repository: Sulu\Bundle\SecurityBundle\Entity\UserRepository

For the `Role` entity (`se_roles`):

.. code-block:: yaml

    sulu_security:
        objects:
            role:
                model:                Sulu\Bundle\SecurityBundle\Entity\Role
                repository:           Sulu\Bundle\SecurityBundle\Entity\RoleRepository

For the `Contact` entity (`co_contacts`):

.. code-block:: yaml

    sulu_contact:
        objects:
            contact:
                model:                Sulu\Bundle\ContactBundle\Entity\Contact
                repository:           Sulu\Bundle\ContactBundle\Entity\ContactRepository

For the `Media` entity (`me_media`):

.. code-block:: yaml

    sulu_media:
        objects:
            media:
                model:                Sulu\Bundle\MediaBundle\Entity\Media
                repository:           Sulu\Bundle\MediaBundle\Entity\MediaRepository

.. warning::

    If you override the entities you should provide a data migration script to avoid data lose.
