<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ManifesteController extends AbstractController
{
    #[Route('/manifeste', name: 'app_manifeste')]
    public function index(): Response
    {
        return $this->render('manifeste/index.html.twig', [
            'controller_name' => 'ManifesteController',
        ]);
    }
}

