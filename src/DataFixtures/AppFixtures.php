<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setFirstname('Admin');
        $admin->setLastname('System');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPoints(10000);
        $admin->setActif(true);
        
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'admin123');
        $admin->setPassword($hashedPassword);
        
        $manager->persist($admin);

        $user = new User();
        $user->setEmail('user@example.com');
        $user->setFirstname('John');
        $user->setLastname('Doe');
        $user->setRoles(['ROLE_USER']);
        $user->setPoints(500);
        $user->setActif(true);
        
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'user123');
        $user->setPassword($hashedPassword);
        
        $manager->persist($user);

        $products = [
            [
                'name' => 'Verre à vin rouge Premium',
                'price' => 100,
                'category' => 'Verres à vin',
                'description' => 'Verre élégant pour déguster les meilleurs vins rouges'
            ],
            [
                'name' => 'Verre à champagne Cristal',
                'price' => 150,
                'category' => 'Verres à champagne',
                'description' => 'Flûte en cristal pour savourer le champagne'
            ],
            [
                'name' => 'Verre à vin blanc',
                'price' => 80,
                'category' => 'Verres à vin',
                'description' => 'Verre parfait pour les vins blancs et rosés'
            ]
        ];

        foreach ($products as $productData) {
            $product = new Product();
            $product->setName($productData['name']);
            $product->setPrice($productData['price']);
            $product->setFormatedPrice($productData['price'] . ' points');
            $product->setCategory($productData['category']);
            $product->setDescription($productData['description']);
            $product->setCreatedBy($admin); // Assigner le créateur
            
            $manager->persist($product);
        }

        $manager->flush();
    }
}
