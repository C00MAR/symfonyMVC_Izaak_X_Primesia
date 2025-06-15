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
        private UserPasswordHasherInterface $passwordHasher,
        private string $projectDir
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
                'name' => 'Linden Nº 1 Red',
                'price' => 100,
                'category' => 'Red Wine',
                'description' => 'Mouth-blown glass No.1 Red from the Linden series with its large lime leaf shape will open fully structured wines aging in oak barrels. It works great with Pinot Noir, Merlot, or Nebbiolo varieties.
                                    Our glasses are mouth-blown in the Izaak Reich glassworks using lead-free crystal glass. With each package, you will receive a certificate of authenticity with the signature of the master glassmaker who made these glasses for you.
                                    The author of the design is Rony Plesl, a leading Czech artist, sculptor, designer, and professor.',
                'images' => ['LindenRed-1.jpg', 'LindenRed-2.jpg', 'LindenRed-3.jpg', 'LindenRed-4.jpg']
            ],
            [
                'name' => 'Linden Nº 2 Red & White',
                'price' => 150,
                'category' => 'Red Wine & White Wine',
                'description' => 'Mouth-blown glass No.2 Red&White from the Linden series will suit full-bodied white wines aged in barrel and bottle (especially Chardonnay, Pinot blanc, or Riesling) and fresh red wines of younger vintages (especially Pinot noir, Blaufränkisch, Sangiovese).
                                    Our glasses are mouth-blown in the Izaak Reich glassworks using lead-free crystal glass. With each package, you will receive a certificate of authenticity with the signature of the master glassmaker who made these glasses for you.
                                    The author of the design is Rony Plesl, a leading Czech artist, sculptor, designer, and professor.',
                'images' => ['LindenRedWhite-1.jpg', 'LindenRedWhite-2.jpg', 'LindenRedWhite-3.jpg', 'LindenRedWhite-4.jpg']
            ],
            [
                'name' => 'Linden Nº 3 White',
                'price' => 80,
                'category' => 'White Wine',
                'description' => 'Mouth-blown glass No.2 Red&White from the Linden series will suit full-bodied white wines aged in barrel and bottle (especially Chardonnay, Pinot blanc, or Riesling) and fresh red wines of younger vintages (especially Pinot noir, Blaufränkisch, Sangiovese).
                                    Our glasses are mouth-blown in the Izaak Reich glassworks using lead-free crystal glass. With each package, you will receive a certificate of authenticity with the signature of the master glassmaker who made these glasses for you.
                                    The author of the design is Rony Plesl, a leading Czech artist, sculptor, designer, and professor.',
                'images' => ['LindenWhite-1.jpg', 'LindenWhite-2.jpg', 'LindenWhite-3.jpg', 'LindenWhite-4.jpg']
            ],
            [
                'name' => 'Set de verres dégustation',
                'price' => 250,
                'category' => 'Sets',
                'description' => 'Ensemble complet de 6 verres pour la dégustation professionnelle. Idéal pour les amateurs de vin souhaitant explorer toutes les nuances gustatives. Comprend verres pour rouge, blanc et champagne.',
                'images' => ['set-degustation-1.jpg', 'set-degustation-2.jpg', 'set-degustation-3.jpg']
            ],
            [
                'name' => 'Verre à Bordeaux Grand Cru',
                'price' => 180,
                'category' => 'Verres à vin',
                'description' => 'Verre spécialement conçu pour les grands crus de Bordeaux. Sa forme unique permet une oxygénation optimale des tanins. Recommandé par les sommelier professionnels.',
                'images' => ['verre-bordeaux-1.jpg', 'verre-bordeaux-2.jpg']
            ],
            [
                'name' => 'Carafe à décanter',
                'price' => 300,
                'category' => 'Accessoires',
                'description' => 'Carafe en cristal pour décanter vos vins les plus précieux. Permet une aération parfaite et une présentation élégante. Indispensable pour les grands vins de garde.',
                'images' => ['carafe-decanter-1.jpg', 'carafe-decanter-2.jpg', 'carafe-decanter-3.jpg', 'carafe-decanter-4.jpg', 'carafe-decanter-5.jpg']
            ]
        ];

        foreach ($products as $productData) {
            $product = new Product();
            $product->setName($productData['name']);
            $product->setPrice($productData['price']);
            $product->setFormatedPrice($productData['price'] . ' points');
            $product->setCategory($productData['category']);
            $product->setDescription($productData['description']);
            $product->setCreatedBy($admin);
            
            $manager->persist($product);
            $manager->flush();
            
            $this->createProductImages($product->getId(), $productData['images']);
            $product->setImages($productData['images']);
            
            $manager->persist($product);
        }

        $manager->flush();
    }

    private function createProductImages(int $productId, array $imageNames): void
    {
        $productDir = $this->projectDir . '/public/uploads/products/' . $productId;
        
        if (!is_dir($productDir)) {
            mkdir($productDir, 0755, true);
        }

        $colors = ['#8B0000', '#FFD700', '#F5F5DC', '#4B0082', '#800020', '#2F4F4F', '#DC143C', '#228B22'];
        
        foreach ($imageNames as $index => $imageName) {
            $imagePath = $productDir . '/' . $imageName;
            
            if (!file_exists($imagePath)) {
                $this->createDemoImage($imagePath, $colors[$index % count($colors)], "Image " . ($index + 1));
            }
        }
    }

    // THIS FUNCTION IS MADE WITH AI :
    private function createDemoImage(string $path, string $hexColor, string $text): void
    {
        $width = 800;
        $height = 600;
        
        $image = imagecreatetruecolor($width, $height);
        
        $rgb = sscanf($hexColor, "#%02x%02x%02x");
        $background = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
        $white = imagecolorallocate($image, 255, 255, 255);
        $gray = imagecolorallocate($image, 128, 128, 128);
        
        imagefill($image, 0, 0, $background);
        
        $centerX = $width / 2;
        $centerY = $height / 2;
        
        $radius = min($width, $height) * 0.25;
        imageellipse($image, $centerX, $centerY, $radius * 2, $radius * 2, $white);
        imageellipse($image, $centerX, $centerY, ($radius * 2) - 6, ($radius * 2) - 6, $gray);
        
        for ($i = 0; $i < 3; $i++) {
            $x = $centerX + cos($i * 2 * M_PI / 3) * $radius * 0.6;
            $y = $centerY + sin($i * 2 * M_PI / 3) * $radius * 0.6;
            imageellipse($image, $x, $y, 40, 40, $white);
        }
        
        $lines = explode(' ', $text);
        $y = $centerY - (count($lines) * 15);
        foreach ($lines as $line) {
            $x = $centerX - (strlen($line) * 8);
            imagestring($image, 5, max(10, $x), $y, $line, $white);
            $y += 30;
        }
        
        $filename = basename($path, '.jpg');
        imagestring($image, 2, 10, $height - 20, $filename, $gray);
        
        imagejpeg($image, $path, 90);
        imagedestroy($image);
    }
}
