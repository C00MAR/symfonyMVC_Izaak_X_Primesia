<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class Admin/PointsController extends AbstractController
{
    #[Route('/admin/points', name: 'app_admin_points')]
    public function index(): Response
    {
        return $this->render('admin/points/index.html.twig', [
            'controller_name' => 'Admin/PointsController',
        ]);
    }
}
