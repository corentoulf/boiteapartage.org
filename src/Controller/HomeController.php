<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/app/accueil', name: 'app_auth_home')]
    public function appHome(): Response
    {
        return $this->render('app_home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
