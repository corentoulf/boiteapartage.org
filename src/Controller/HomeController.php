<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\UserCircle;
use Doctrine\ORM\EntityManagerInterface;
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
    public function appHome(EntityManagerInterface $em): Response
    {

        $user = $this->getUser();
        $userCircles = $em->getRepository(UserCircle::class)->findBy(['user_id' => $user->getId()]);
        $userItems = $em->getRepository(Item::class)->findBy(['owner' => $user->getId()]);

        return $this->render('app_home/index.html.twig', [
            'controller_name' => 'HomeController',
            'userCircles' => $userCircles,
            'userItems' => $userItems
        ]);
    }
}
