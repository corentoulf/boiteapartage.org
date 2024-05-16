<?php

namespace App\Controller;

use App\Entity\Circle;
use App\Entity\UserCircle;
use App\Form\CircleFormType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AppCircleController extends AbstractController
{
    #[Route('/app/cercle', name: 'app_circle')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $circles = $em->getRepository(Circle::class)->findBy(['created_by' => $user->getId()]);
        $circlesOther = $em->getRepository(UserCircle::class)->findBy(['user_id' => $user->getId()]);
        
        return $this->render('app_circle/index.html.twig', [
            'controller_name' => 'AppCircleController',
            'circles' => $circles,
            'otherCircles' => $circlesOther
        ]);
    }

    #[Route('/app/cercle/nouveau', name: 'app_circle_create')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $circle = new Circle();
        $user = $this->getUser();
        $form = $this->createForm(CircleFormType::class, $circle);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $circle = $form->getData();

            $circle->setCreatedBy($user);
            $circle->setCreatedAt(new DateTime('now'));

            $em->persist($circle);
            $em->flush();

            $this->addFlash('success', 'Le cercle a bien été créé.');
            return $this->redirectToRoute('app_circle');
        }


        return $this->render('app_circle/create.html.twig', [
            'controller_name' => 'AppCircleController',
            'form' => $form
        ]);
    }
}
