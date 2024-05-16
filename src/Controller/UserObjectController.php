<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserObjectController extends AbstractController
{
    #[Route('/app/placard', name: 'app_user_object')]
    public function index(): Response
    {
        return $this->render('app_user_object/index.html.twig', [
            'controller_name' => 'UserObjectController',
        ]);
    }
}
