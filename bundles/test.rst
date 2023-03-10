TestBundle
==========

Writing automatic tests for a Sulu project will be similar to tests in a usual
Symfony project. Therefore you should have a look at their `test documentation`_.


Sulu's Kernel Context
---------------------

Sulu adds an additional layer to the service container of Symfony. This is
the kernel context. It separates the *admin* area from the *website* area.

When you start writing integration or functional tests you have to keep in mind
that some services are only available in either context.

Integration Tests
-----------------

In integration tests you may have depencencies on other services. If a service
is only available in the website context you will get issues, because the
kernel is in admin context per default.

Therefore Sulu provides an extended `KernelTestCase`_ to specify the kernel
context.

.. code-block:: php
    
    // tests/Integration/Service/NewsletterGeneratorTest.php
    namespace App\Tests\Integration\Service;
    
    use Sulu\Bundle\TestBundle\Testing\KernelTestCase;
    use Sulu\Component\HttpKernel\SuluKernel;
    
    class NewsletterGeneratorTest extends KernelTestCase
    {
        public function testSomething()
        {
            self::bootKernel([
                'sulu.context' => SuluKernel::CONTEXT_WEBSITE
            ]);

            // ...
        }
    }

This will boot the kernel in the website context so that you have access to
the services living in it.

Functional Tests
----------------

In functional tests you test your application from a higher level. Instead of
single methods or algorithms you run the functions from the same level as the
user.

Similar to integration tests you also have to boot the kernel in the website
context if you want to test controllers and services living in that context.

Use Sulu's `SuluTestCase`_ to create a client in the website context and define
your expectations to the called action.

.. code-block:: php
    
    // tests/Functional/Controller/Website/RegistrationControllerTest.php
    namespace App\Tests\Functional\Controller\Website;
    
    use Sulu\Bundle\TestBundle\Testing\SuluTestCase;
    use Sulu\Component\HttpKernel\SuluKernel;
    
    class RegistrationControllerTest extends SuluTestCase
    {
        public function testIndexAction(): void
        {
            $client = static::createWebsiteClient();
            $crawler = $client->request('GET', '/registration/');

            $this->assertResponseIsSuccessful();
            $this->assertSelectorTextContains('h1', 'Join our Community');

            // ...
        }
    }

Actually calling ``$client = static::createWebsiteClient()`` is equal to this
    
.. code-block:: php
    
    $client = static::createClient([
        'sulu.context' => SuluKernel::CONTEXT_WEBSITE,
    ]);

Visit the documentation of the `DOM Crawler`_ to find out how to use it to make
your assertions.

Logging in Users (Authentication)
---------------------------------

If you need a logged in user to test secured actions, Sulu provides a test
user, which you can use.

.. code-block:: php
    
    class ArticleAdminControllerTest extends SuluTestCase
    {
        public function testIndexAction(): void
        {
            $client = static::createClient();
        
            $user = $this->getTestUser();
            $client->loginUser($user);
        }
    }

The user you get, is an entity of the type ``Sulu\Bundle\SecurityBundle\Entity\User``,
has the role *ROLE_USER* and it is automatically granted access if authorization
is checked.

Database purging
----------------

If you are doing database manipulations in your tests, and need a clean database
before you run your tests. You may want to use Sulu's database purging helper.

.. code-block:: php
    
    class ActivityRepositoryTest extends SuluTestCase
    {
        public function setUp(): void
        {
            static::purgeDatabase();
        }
    }

This purges the database before each test, that is defined in the test class.
If you only want to purge the database once before your tests run you can use
it in ``setUpBeforeClass()`` instead of ``setUp()``.


.. _test documentation: https://symfony.com/doc/current/testing.html
.. _KernelTestCase: https://github.com/sulu/sulu/blob/2.5/src/Sulu/Bundle/TestBundle/Testing/KernelTestCase.php
.. _SuluTestCase: https://github.com/sulu/sulu/blob/2.5/src/Sulu/Bundle/TestBundle/Testing/SuluTestCase.php
.. _DOM Crawler: https://symfony.com/doc/current/testing/dom_crawler.html
