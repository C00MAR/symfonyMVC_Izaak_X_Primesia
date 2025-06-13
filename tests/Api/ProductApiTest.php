<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProductApiTest extends ApiTestCase
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    protected function setUp(): void
    {
        parent::setUp();
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->passwordHasher = $kernel->getContainer()->get(UserPasswordHasherInterface::class);
        
        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function testGetProductCollectionAsAdmin(): void
    {
        $admin = new User();
        $admin->setEmail('admin@test.com');
        $admin->setFirstname('Admin');
        $admin->setLastname('Test');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPoints(1000);
        $admin->setActif(true);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'password'));
        
        $this->entityManager->persist($admin);

        $product = new Product();
        $product->setName('Test Product');
        $product->setPrice(100);
        $product->setFormatedPrice('100 points');
        $product->setCategory('Test');
        $product->setDescription('Test description');
        $product->setCreatedBy($admin);
        
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $client = self::createClient();
        $client->loginUser($admin);

        $response = $client->request('GET', '/api/admin/products');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testGetProductCollectionAsUserForbidden(): void
    {
        $user = new User();
        $user->setEmail('user@test.com');
        $user->setFirstname('User');
        $user->setLastname('Test');
        $user->setRoles(['ROLE_USER']);
        $user->setPoints(500);
        $user->setActif(true);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $client = self::createClient();
        $client->loginUser($user);

        $client->request('GET', '/api/admin/products');

        $this->assertResponseStatusCodeSame(403);
    }

    public function testGetProductAsAdmin(): void
    {
        $admin = new User();
        $admin->setEmail('admin2@test.com');
        $admin->setFirstname('Admin');
        $admin->setLastname('Test2');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPoints(1000);
        $admin->setActif(true);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'password'));
        
        $this->entityManager->persist($admin);

        $product = new Product();
        $product->setName('Test Product 2');
        $product->setPrice(150);
        $product->setFormatedPrice('150 points');
        $product->setCategory('Test');
        $product->setDescription('Test description 2');
        $product->setCreatedBy($admin);
        
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $client = self::createClient();
        $client->loginUser($admin);

        $response = $client->request('GET', '/api/admin/products/' . $product->getId());

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'name' => 'Test Product 2',
        ]);
    }
}
