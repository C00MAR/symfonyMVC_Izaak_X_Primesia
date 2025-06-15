<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductTypeForm;
use App\Repository\ProductRepository;
use App\Service\FileUploadService;
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
    public function __construct(
        private FileUploadService $fileUploadService,
    ) {
    }

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

            $imageFiles = $form->get('imageFiles')->getData();
            if ($imageFiles) {
                try {
                    $uploadedFiles = [];
                    foreach ($imageFiles as $imageFile) {
                        if ($imageFile) {
                            $fileName = $this->fileUploadService->uploadSingleProductImage($imageFile, $product->getId());
                            $uploadedFiles[] = $fileName;
                        }
                    }
                    
                    if (!empty($uploadedFiles)) {
                        $product->setImages($uploadedFiles);
                        $entityManager->flush();
                    }
                } catch (\Exception $e) {
                    $this->addFlash('error', $e->getMessage());
                    return $this->render('admin/product/create.html.twig', [
                        'form' => $form,
                    ]);
                }
            }
            
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
            $imageFiles = $form->get('imageFiles')->getData();
            if ($imageFiles && !empty($imageFiles)) {
                try {
                    $uploadedFiles = [];
                    foreach ($imageFiles as $imageFile) {
                        if ($imageFile) {
                            $fileName = $this->fileUploadService->uploadSingleProductImage($imageFile, $product->getId());
                            $uploadedFiles[] = $fileName;
                        }
                    }
                    
                    if (!empty($uploadedFiles)) {
                        $currentImages = $product->getImages();
                        $allImages = array_merge($currentImages, $uploadedFiles);
                        $product->setImages($allImages);
                    }
                } catch (\Exception $e) {
                    $this->addFlash('error', $e->getMessage());
                    return $this->render('admin/product/edit.html.twig', [
                        'form' => $form,
                        'product' => $product,
                    ]);
                }
            }

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
        $this->fileUploadService->deleteAllProductImages($product->getId());

        $entityManager->remove($product);
        $entityManager->flush();
        
        $this->addFlash('success', 'Produit supprimé avec succès !');
        return $this->redirectToRoute('app_admin_product_list');
    }

    #[Route('/{id}/delete-image/{imageName}', name: 'app_admin_product_delete_image', methods: ['POST'])]
    public function deleteImage(Product $product, string $imageName, EntityManagerInterface $entityManager): Response
    {
        $this->fileUploadService->deleteProductImage($product->getId(), $imageName);
        
        $product->removeImage($imageName);
        $entityManager->flush();
        
        $this->addFlash('success', 'Image supprimée avec succès !');
        return $this->redirectToRoute('app_admin_product_edit', ['id' => $product->getId()]);
    }
}
