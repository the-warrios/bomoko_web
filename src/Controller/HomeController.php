<?php

namespace App\Controller;

use App\Service\SignalementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(SignalementService $signalementService): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Aucun utilisateur connecté.');
        }

        if(method_exists($user, 'getId'))

        $userId = $user->getId();

        // Utiliser le service avec l'ID de l'utilisateur
        $signalement = $signalementService->getRportForHome($userId);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'user' => $user,
            'signalement' => $signalement,
        ]);
    }
}
