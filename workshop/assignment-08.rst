Assignment 8 — Add a custom ``Location`` entity
###############################################

Events will later reference a **shared** address. The first step is a plain **Doctrine** ``Location`` table with the
fields you need in the admin and on the site.

.. note::

   The reference branch ``assignment/08`` extends the brief with **number** and **postalCode**; you can keep only the
   fields from the assignment text if you prefer a smaller model.

What you learn
==============

* `Defining an entity with PHP attributes`__ and running ``doctrine:schema:update`` in a Sulu (Symfony) project.

.. __: https://symfony.com/doc/current/doctrine.html#creating-an-entity-class

Prerequisites
=============

* A working database connection in ``.env``.

Reference implementation
========================

.. code-block:: php

    // src/Entity/Location.php
    <?php

    declare(strict_types=1);

    namespace App\Entity;

    use App\Repository\LocationRepository;
    use Doctrine\DBAL\Types\Types;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity(repositoryClass: LocationRepository::class)]
    class Location
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: Types::INTEGER)]
        private ?int $id = null;

        #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
        private ?string $name = null;

        #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
        private ?string $street = null;

        #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
        private ?string $number = null;

        #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
        private ?string $postalCode = null;

        #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
        private ?string $city = null;

        #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
        private ?string $countryCode = null;

        // ... getters and setters ...
    }

Add ``LocationRepository`` with the usual ``ServiceEntityRepository`` boilerplate (see the branch) and then:

.. code-block:: bash

   $ bin/adminconsole doctrine:schema:update --force
   $ bin/adminconsole doctrine:schema:validate

See also
========

* `Databases and Doctrine (Symfony) <https://symfony.com/doc/current/doctrine.html>`__

Reference branch
================

``assignment/08``.
