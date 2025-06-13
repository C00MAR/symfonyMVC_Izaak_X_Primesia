<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductTypeForm;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/products')]
#[IsGranted('ROLE_ADMIN')]
final class ProductController extends AbstractController
{
    #[Route('/', name: 'app_admin_product_list')]
    public function list(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        
        return $this->render('admin/product/list.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/create', name: 'app_admin_product_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductTypeForm::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setCreatedBy($this->getUser());

            $entityManager->persist($product);
            $entityManager->flush();
            
            $this->addFlash('success', 'Produit créé avec succès !');
            return $this->redirectToRoute('app_admin_product_list');
        }

        return $this->render('admin/product/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_product_edit')]
    public function edit(Product $product, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductTypeForm::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            $this->addFlash('success', 'Produit modifié avec succès !');
            return $this->redirectToRoute('app_admin_product_list');
        }

        return $this->render('admin/product/edit.html.twig', [
            'form' => $form,
            'product' => $product,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_admin_product_delete', methods: ['POST'])]
    public function delete(Product $product, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($product);
        $entityManager->flush();
        
        $this->addFlash('success', 'Produit supprimé avec succès !');
        return $this->redirectToRoute('app_admin_product_list');
    }
}
