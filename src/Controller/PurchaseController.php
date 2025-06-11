<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\PurchaseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class PurchaseController extends AbstractController
{
    #[Route('/purchase/{id}', name: 'app_purchase')]
    #[IsGranted('ROLE_USER')]
    public function purchase(
        Product $product, 
        PurchaseService $purchaseService,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        
        if (!$user->isActif()) {
            $this->addFlash('error', 'Votre compte est désactivé, vous ne pouvez pas acheter.');
            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }

        if ($purchaseService->purchase($user, $product)) {
            $entityManager->flush();
            $this->addFlash('success', 'Achat effectué avec succès !');
        } else {
            $this->addFlash('error', 'Points insuffisants pour cet achat.');
        }

        return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
    }
}
