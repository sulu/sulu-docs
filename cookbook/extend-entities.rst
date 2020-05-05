Extend Entities
===============

Sulu has a very easy way to extend and replace the internal entities. This feature is not
implemented for each entity but it will be implemented for all soon.

These entities are ready to extend:

* User
* Role
* Contact
* Media

You can extend all of them in the same way. Therefore we explain it for `User` here.

Create a Entity
---------------

Create your own Entity for example in the `src` folder and extends the Entity with the Sulu
`User` class.

.. code-block:: php

    <?php

    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Sulu\Bundle\SecurityBundle\Entity\User as SuluUser;

    /**
     * User
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

Configuration
-------------

You can specify your new entity and if it exists your repository the
configuration section in the file ``config/packages/*``.

For the `User` entity:

.. code-block:: yaml

    # config/packages/sulu_security.yaml
    sulu_security:
        objects:
            user:
                model: App\Entity\User
                repository: Sulu\Bundle\SecurityBundle\Entity\UserRepository

For the `Role` entity:

.. code-block:: yaml

    # config/packages/sulu_security.yaml
    sulu_security:
        objects:
            role:
                model:                Sulu\Bundle\SecurityBundle\Entity\Role
                repository:           Sulu\Bundle\SecurityBundle\Entity\RoleRepository

For the `Contact` entity:

.. code-block:: yaml

    # config/packages/sulu_contact.yaml
    sulu_contact:
        objects:
            contact:
                model:                Sulu\Bundle\ContactBundle\Entity\Contact
                repository:           Sulu\Bundle\ContactBundle\Entity\ContactRepository

For the `Account` entity:

.. code-block:: yaml

    # config/packages/sulu_contact.yaml
    sulu_contact:
        objects:
            account:
                model:                Sulu\Bundle\ContactBundle\Entity\Account
                repository:           Sulu\Bundle\ContactBundle\Entity\AccountRepository

For the `Media` entity:

.. code-block:: yaml

    # config/packages/sulu_media.yaml
    sulu_media:
        objects:
            media:
                model:                Sulu\Bundle\MediaBundle\Entity\Media
                repository:           Sulu\Bundle\MediaBundle\Entity\MediaRepository

.. warning::

    If you override the entities you lose your old tables and data. You should provide
    a upgrade script.
