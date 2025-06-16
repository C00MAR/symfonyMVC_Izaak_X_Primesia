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
                'description' => 'Mouth-blown glass No.1 Red from the Linden series with its large lime leaf shape will open fully structured wines aging in oak barrels. It works great with Pinot Noir, Merlot, or Nebbiolo varieties.',
                'imageUrls' => [
                    'https://www.izaakreich.com/res/crc-960671653/products/images/1198x1198crop/zx-izaakreich-linden-detail-n1-1.webp',
                    'https://www.izaakreich.com/res/crc-2334150058/products/images/1198x1198crop/x5-izaakreich-linden-detail-n1-2.webp',
                    'https://www.izaakreich.com/res/crc-2421355163/products/images/1198x1198crop/x7-izaakreich-linden-detail-1.webp',
                    'https://www.izaakreich.com/res/crc-549190153/products/images/1198x1198crop/t4-izaakreich-linden-detail-2.webp'
                ]
            ],
            [
                'name' => 'Linden Nº 2 Red & White',
                'price' => 150,
                'category' => 'Red Wine & White Wine',
                'description' => 'Mouth-blown glass No.2 Red&White from the Linden series will suit full-bodied white wines aged in barrel and bottle (especially Chardonnay, Pinot blanc, or Riesling) and fresh red wines of younger vintages (especially Pinot noir, Blaufränkisch, Sangiovese).',
                'imageUrls' => [
                    'https://www.izaakreich.com/res/crc-2995887537/products/images/1198x1198crop/ho-izaakreich-linden-detail-n2-1.webp',
                    'https://www.izaakreich.com/res/crc-3657036641/products/images/1198x1198crop/9h-izaakreich-linden-detail-n2-2.webp',
                    'https://www.izaakreich.com/res/crc-2421355163/products/images/1198x1198crop/x7-izaakreich-linden-detail-1.webp',
                    'https://www.izaakreich.com/res/crc-549190153/products/images/1198x1198crop/t4-izaakreich-linden-detail-2.webp'
                ]
            ],
            [
                'name' => 'Linden Nº 3 White',
                'price' => 80,
                'category' => 'White Wine',
                'description' => 'Mouth-blown glass No.3 White from the Linden series, perfect for fresh white wines and light varietals.',
                'imageUrls' => [
                    'https://www.izaakreich.com/res/crc-552760266/products/images/1198x1198crop/4b-izaakreich-linden-detail-n3-1.webp',
                    'https://www.izaakreich.com/res/crc-2320450881/products/images/1198x1198crop/ky-izaakreich-linden-detail-n3-2.webp',
                    'https://www.izaakreich.com/res/crc-2421355163/products/images/1198x1198crop/x7-izaakreich-linden-detail-1.webp',
                    'https://www.izaakreich.com/res/crc-549190153/products/images/1198x1198crop/t4-izaakreich-linden-detail-2.webp'
                ]
            ],
            [
                'name' => 'Linden Nº 4 Sparkling',
                'price' => 250,
                'category' => 'Sparkling Wine',
                'description' => 'Mouth-blown No.4 Sparkling glass from the Linden series, in the shape of a folded lime leaf, will go well with all sparkling wines, but its full potential can only be exploited with wines produced by bottle fermentation, such as Champagne, Crémant, Cava, or Franciacorta.',
                'imageUrls' => [
                    'https://www.izaakreich.com/res/crc-2987964744/products/images/1198x1198crop/h4-izaakreich-linden-detail-n4-1.webp',
                    'https://www.izaakreich.com/res/crc-1971773342/products/images/1198x1198crop/xf-izaakreich-linden-detail-n4-2.webp',
                    'https://www.izaakreich.com/res/crc-2421355163/products/images/1198x1198crop/x7-izaakreich-linden-detail-1.webp',
                    'https://www.izaakreich.com/res/crc-549190153/products/images/1198x1198crop/t4-izaakreich-linden-detail-2.webp'
                ]
            ],
            [
                'name' => 'Linden Nº 5 Brandy',
                'price' => 180,
                'category' => 'Spirits',
                'description' => 'Mouth-blown No.5 Brandy glass from the Linden series with its inverted lime leaf shape is ideal for long-aged Brandy such as Cognac or Armagnac.',
                'imageUrls' => [
                    'https://www.izaakreich.com/res/crc-458633740/products/images/1198x1198crop/lp-izaakreich-linden-detail-n5-1.webp',
                    'https://www.izaakreich.com/res/crc-3270668671/products/images/1198x1198crop/nc-izaakreich-linden-detail-n5-2.webp',
                    'https://www.izaakreich.com/res/crc-2421355163/products/images/1198x1198crop/x7-izaakreich-linden-detail-1.webp',
                    'https://www.izaakreich.com/res/crc-549190153/products/images/1198x1198crop/t4-izaakreich-linden-detail-2.webp'
                ]
            ],
            [
                'name' => 'Linden Nº 6 Rum & Whisky',
                'price' => 300,
                'category' => 'Spirits',
                'description' => 'The mouth-blown No. 6 Rum & Whisky glass from the Linden series is well-suited for serving quality Caribbean rums and long-aged Scotch whiskies.',
                'imageUrls' => [
                    'https://www.izaakreich.com/res/crc-2378307605/products/images/1198x1198crop/4k-izaakreich-linden-detail-n6-1.webp',
                    'https://www.izaakreich.com/res/crc-1177777345/products/images/1198x1198crop/09-izaakreich-linden-detail-n6-2.webp',
                    'https://www.izaakreich.com/res/crc-2421355163/products/images/1198x1198crop/x7-izaakreich-linden-detail-1.webp',
                    'https://www.izaakreich.com/res/crc-549190153/products/images/1198x1198crop/t4-izaakreich-linden-detail-2.webp'
                ]
            ],
            [
                'name' => 'Linden Nº 7 Digestif',
                'price' => 160,
                'category' => 'Spirits',
                'description' => 'The mouth-blown No.7 Digestive glass from the Linden series will beautifully finish your festive dinner, whether you fill it with Italian grappa or French calvados.',
                'imageUrls' => [
                    'https://www.izaakreich.com/res/crc-1031335117/products/images/1198x1198crop/vp-izaakreich-linden-detail-n7-1.webp',
                    'https://www.izaakreich.com/res/crc-4126770939/products/images/1198x1198crop/9k-izaakreich-linden-detail-n7-2.webp',
                    'https://www.izaakreich.com/res/crc-2421355163/products/images/1198x1198crop/x7-izaakreich-linden-detail-1.webp',
                    'https://www.izaakreich.com/res/crc-549190153/products/images/1198x1198crop/t4-izaakreich-linden-detail-2.webp'
                ]
            ],
            [
                'name' => 'Linden Water Optique',
                'price' => 80,
                'category' => 'Water',
                'description' => 'This exquisite mouth-blown water glass resembles broad linden leaves and is crafted using the Optique glass method, which produces a beautiful interplay of light and reflections.',
                'imageUrls' => [
                    'https://www.izaakreich.com/res/crc-2408188175/products/images/1198x1198crop/b0-izaakreich-linden-detail-gls-clear-1.webp',
                    'https://www.izaakreich.com/res/crc-339408436/products/images/1198x1198crop/ew-izaakreich-linden-detail-gls-clear-2.webp',
                    'https://www.izaakreich.com/res/crc-2421355163/products/images/1198x1198crop/x7-izaakreich-linden-detail-1.webp',
                    'https://www.izaakreich.com/res/crc-549190153/products/images/1198x1198crop/t4-izaakreich-linden-detail-2.webp'
                ]
            ],
            [
                'name' => 'Linden Carafe & Water Optique',
                'price' => 140,
                'category' => 'Water',
                'description' => 'These exquisite mouth-blown glasses and water carafes resemble broad linden leaves and are crafted using the Optique glass method, which produces a beautiful interplay of light and reflections.',
                'imageUrls' => [
                    'https://www.izaakreich.com/res/crc-1475138913/products/images/1198x1198crop/fp-izaakreich-linden-detail-car-clear-1.webp',
                    'https://www.izaakreich.com/res/crc-3420450964/products/images/1198x1198crop/ky-izaakreich-linden-detail-car-clear-2.webp',
                    'https://www.izaakreich.com/res/crc-2421355163/products/images/1198x1198crop/x7-izaakreich-linden-detail-1.webp',
                    'https://www.izaakreich.com/res/crc-549190153/products/images/1198x1198crop/t4-izaakreich-linden-detail-2.webp'
                ]
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
            
            $downloadedImages = $this->downloadImagesFromUrls($product->getId(), $productData['imageUrls']);
            $product->setImages($downloadedImages);
            
            $manager->persist($product);
        }

        $manager->flush();
    }

    private function downloadImagesFromUrls(int $productId, array $imageUrls): array
    {
        $productDir = $this->projectDir . '/public/uploads/products/' . $productId;
        
        if (!is_dir($productDir)) {
            mkdir($productDir, 0755, true);
        }

        $downloadedFiles = [];
        
        foreach ($imageUrls as $index => $url) {
            try {
                $imageContent = file_get_contents($url);
                
                if ($imageContent === false) {
                    echo "Erreur lors du téléchargement de l'image: $url\n";
                    continue;
                }
                
                $pathInfo = pathinfo(parse_url($url, PHP_URL_PATH));
                $extension = $pathInfo['extension'] ?? 'jpg';
                
                if (empty($extension) || !in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $imageInfo = getimagesizefromstring($imageContent);
                    if ($imageInfo !== false) {
                        $extension = match ($imageInfo[2]) {
                            IMAGETYPE_JPEG => 'jpg',
                            IMAGETYPE_PNG => 'png',
                            IMAGETYPE_GIF => 'gif',
                            IMAGETYPE_WEBP => 'webp',
                            default => 'jpg'
                        };
                    } else {
                        $extension = 'jpg';
                    }
                }
                
                $filename = sprintf('image_%d_%s.%s', $index + 1, uniqid(), $extension);
                $filePath = $productDir . '/' . $filename;
                
                if (file_put_contents($filePath, $imageContent) !== false) {
                    $downloadedFiles[] = $filename;
                    echo "Image téléchargée: $filename\n";
                } else {
                    echo "Erreur lors de la sauvegarde de l'image: $filename\n";
                }
                
                usleep(100000);
                
            } catch (\Exception $e) {
                echo "Erreur lors du téléchargement de l'image $url: " . $e->getMessage() . "\n";
                continue;
            }
        }
        
        return $downloadedFiles;
    }
}
