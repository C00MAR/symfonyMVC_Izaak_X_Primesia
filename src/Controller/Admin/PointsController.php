<?php

namespace App\Controller\Admin;

use App\Message\PointsDistributionMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/points')]
#[IsGranted('ROLE_ADMIN')]
final class PointsController extends AbstractController
{
    #[Route('/', name: 'app_admin_points')]
    public function index(): Response
    {
        return $this->render('admin/points/index.html.twig');
    }

    #[Route('/distribute', name: 'app_admin_points_distribute', methods: ['POST'])]
    public function distribute(MessageBusInterface $messageBus): Response
    {
        $messageBus->dispatch(new PointsDistributionMessage(1000));
        
        $this->addFlash('success', '1000 points seront distribués à tous les utilisateurs actifs !');
        return $this->redirectToRoute('app_admin_points');
    }
}
