<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemFormType;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AppItemController extends AbstractController
{
    #[Route('/app/placard', name: 'app_item')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $userItems = $em->getRepository(Item::class)->findBy(['owner' => $user->getId()]);
        return $this->render('app_item/index.html.twig', [
            'controller_name' => 'AppObjectController',
            'userItems' => $userItems
        ]);
    }

    #[Route('/app/placard/ajout', name: 'app_item_create')]
    public function createItem(Request $request, EntityManagerInterface $em): Response
    {
        $item = new Item();
        $user = $this->getUser();
        $item->setOwner($user);
        $form = $this->createForm(ItemFormType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $nextAction = $form->get('submitAndAdd')->isClicked()
                ? 'app_item_create'
                : 'app_item';
            $item->setCreatedAt(new DateTimeImmutable('now'));
            $em->persist($item);
            $em->flush();

            $this->addFlash('success', 'L\'élément a bien été ajouté à votre placard.');
            return $this->redirectToRoute($nextAction);
        }


        return $this->render('app_item/create.html.twig', [
            'controller_name' => 'AppCircleController',
            'form' => $form
        ]);
    }
}
