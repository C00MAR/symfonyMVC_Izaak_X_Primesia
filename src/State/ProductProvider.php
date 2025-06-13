<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\SecurityBundle\Security;

final class ProductProvider implements ProviderInterface
{
    public function __construct(
        private ProductRepository $productRepository,
        private Security $security
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $user = $this->security->getUser();
        
        if (!$user) {
            return null;
        }

        if ($operation instanceof \ApiPlatform\Metadata\GetCollection) {
            return $this->productRepository->findBy(['createdBy' => $user]);
        }

        if (isset($uriVariables['id'])) {
            $product = $this->productRepository->find($uriVariables['id']);
            
            if ($product && $product->getCreatedBy() === $user) {
                return $product;
            }
        }

        return null;
    }
}
