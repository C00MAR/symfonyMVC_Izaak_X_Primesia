<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class Admin/ProductController extends AbstractController
{
    #[Route('/admin/product', name: 'app_admin_product')]
    public function index(): Response
    {
        return $this->render('admin/product/index.html.twig', [
            'controller_name' => 'Admin/ProductController',
        ]);
    }
}
