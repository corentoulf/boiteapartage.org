<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\ItemCircle;
use App\Entity\ItemType;
use App\Form\ItemFormType;
use App\Form\ItemBookFormType;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AppItemController extends AbstractController
{
    #[Route('/app/placard', name: 'app_items')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $userItems = $em->getRepository(Item::class)->findBy(['owner' => $user->getId()]);
        return $this->render('app_item/index.html.twig', [
            'controller_name' => 'AppObjectController',
            'userItems' => $userItems
        ]);
    }

    /*
    #[Route('/app/placard/ajoutsave', name: 'app_item_create_save')]
    public function createItemSave(Request $request, EntityManagerInterface $em): Response
    {
        $item = new Item();
        $user = $this->getUser();
        $item->setOwner($user);
        $form = $this->createForm(ItemFormType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $nextAction = $form->get('submitAndAdd')->isClicked()
                ? 'app_item_create'
                : 'app_items';
            $item->setCreatedAt(new DateTimeImmutable('now'));
            $em->persist($item);

            //handle itemCircle
            //get all circles of the user
            $userCircles = $user->getUserCircles();
            foreach ($userCircles as &$userCircle) {
                $circle = $userCircle->getCircle();
                $itemCircle = new ItemCircle(); //create itemCircle
                $itemCircle->setCircle($circle); //populate circle
                $itemCircle->setItem($item); //populate item
                $em->persist($itemCircle); //persist
            }

            $em->flush();

            $this->addFlash('success', 'L\'objet a bien été ajouté à votre placard.');

            if($nextAction === 'app_item_create'){
                $previousItemType = $item->getItemType();
                $item = new Item();
                $item->setItemType($previousItemType);
                $form = $this->createForm(ItemFormType::class, $item);

                return $this->render('app_item/create_update.html.twig', [
                    'controller_name' => 'AppCircleController',
                    'form' => $form
                ]);
            } else {
                return $this->redirectToRoute($nextAction);
            }
        }


        return $this->render('app_item/create_update.html.twig', [
            'controller_name' => 'AppCircleController',
            'form' => $form
        ]);
    }
    */
    #[Route('/app/placard/ajout', name: 'app_item_create')]
    public function createItem(Request $request, EntityManagerInterface $em): Response
    {
        return $this->render('app_item/create/index.html.twig', [
            'controller_name' => 'AppCircleController'
        ]);
    }

    #[Route('/app/placard/ajout/livre', name: 'app_item_create_book')]
    public function createBookItem(Request $request, EntityManagerInterface $em): Response
    {
        $item = new Item();
        $itemType = $em->getRepository(ItemType::class)->findOneBy(['code' => 'book']);
        $item->setItemType($itemType);
        $user = $this->getUser();
        $item->setOwner($user);
        $form = $this->createForm(ItemBookFormType::class, $item);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $nextAction = 'app_items';
            if(true === $form->get('submitAndAdd')->isClicked()) {
                $nextAction = 'app_item_create_book';
            }
            $item->setCreatedAt(new DateTimeImmutable('now'));
            $em->persist($item);

            //handle itemCircle
            //get all circles of the user
            $userCircles = $user->getUserCircles();
            foreach ($userCircles as &$userCircle) {
                $circle = $userCircle->getCircle();
                $itemCircle = new ItemCircle(); //create itemCircle
                $itemCircle->setCircle($circle); //populate circle
                $itemCircle->setItem($item); //populate item
                $em->persist($itemCircle); //persist
            }

            $em->flush();

            $this->addFlash('success', 'Le livre a bien été ajouté à votre placard.');

            if($nextAction === 'app_item_create'){
                $previousItemType = $item->getItemType();
                $item = new Item();
                $item->setItemType($previousItemType);
                $form = $this->createForm(ItemBookFormType::class, $item);

                return $this->render('app_item/create/book.html.twig', [
                    'controller_name' => 'AppCircleController',
                    'form' => $form
                ]);
            } else {
                return $this->redirectToRoute($nextAction);
            }
        }


        return $this->render('app_item/create/book.html.twig', [
            'controller_name' => 'AppCircleController',
            'form' => $form
        ]);
    }

    #[Route('/app/placard/{id}/update', name: 'app_item_update', requirements: ['id' => '\d+'])]
    public function updateItem(Request $request, EntityManagerInterface $em, int $id): Response
    {
        $item = $em->getRepository(Item::class)->find($id);
        if (!$item) {
            throw $this->createNotFoundException(
                'L\'objet n\'a pas été trouvé'
            );
        }

        $user = $this->getUser();
        $form = $this->createForm(ItemFormType::class, $item, [
            'update_mode' => true,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->persist($item);
            $em->flush();
            $this->addFlash('success', 'L\'objet a bien été mis à jour');
            return $this->redirectToRoute('app_items');
        }


        return $this->render('app_item/create_update.html.twig', [
            'controller_name' => 'AppCircleController',
            'form' => $form,
            'updateMode' => true
        ]);
    }

    #[Route('/app/placard/{id}/delete', name: 'app_item_delete', requirements: ['id' => '\d+'])]
    public function deleteItem(Request $request, EntityManagerInterface $em, int $id): Response
    {
        $item = $em->getRepository(Item::class)->find($id);
        if (!$item) {
            throw $this->createNotFoundException(
                'L\'objet n\'a pas été trouvé'
            );
        }
        $em->remove($item);
        $em->flush();
        $this->addFlash('success', 'L\'objet a bien été supprimé');
        return $this->redirectToRoute('app_items');
    }
}
