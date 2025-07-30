<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\ItemCategory;
use App\Entity\ItemCircle;
use App\Entity\ItemType;
use App\Entity\UserFavoriteItem;
use App\Form\ItemFormType;
use App\Form\ItemBookFormType;
use App\Form\ItemDefaultFormType;
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
        $userItems = $em->getRepository(Item::class)->findBy(
            ['owner' => $user->getId()],
            ['id' => 'DESC']
        );
        $itemCategories = $em->getRepository(ItemCategory::class)->findAllSortByLabel();
        return $this->render('app_item/index.html.twig', [
            'controller_name' => 'AppObjectController',
            'items' => $userItems,
            'itemCategories' => $itemCategories
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

    #[Route('/app/placard/ajout/{code}', name: 'app_item_create_by_category')]
    public function createBookItem(Request $request, EntityManagerInterface $em, ItemCategory $ic): Response
    {
        $item = new Item();
        $user = $this->getUser();
        $item->setOwner($user);

        //set form type according to item category
        switch ($ic->getCode()) {
            case 'bibliotheque':
                $form = $this->createForm(ItemBookFormType::class, $item, [
                    'itemCategory' => $ic,
                    'preferedItemType' => $em->getRepository(ItemType::class)->findOneBy(['code' => 'book'])
                ]);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    
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
                    
                    //if user want to add another of same type
                    if(true === $form->get('submitAndAdd')->isClicked()) {
                        //preset form with current item type
                        $previousItemType = $item->getItemType();
                        $item = new Item();
                        // $item->setItemType($previousItemType);
                        $form = $this->createForm(ItemBookFormType::class, $item, [
                            'itemCategory' => $ic,
                            'preferedItemType' => $previousItemType
                        ]);
                        //redirect to form
                        return $this->render('app_item/create/book.html.twig', [
                            'controller_name' => 'AppItemController',
                            'form' => $form,
                        ]);
                    }
                    //user doesn't want to add another => redirect to items
                    else {
                        return $this->redirectToRoute('app_items');
                    }
                }
                return $this->render('app_item/create/book.html.twig', [
                    'controller_name' => 'AppItemController',
                    'form' => $form,
                ]);
                break;
            
            default:
                $form = $this->createForm(ItemDefaultFormType::class, $item, [
                    'itemCategory' => $ic,
                ]);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    
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
                    
                    //if user want to add another of same type
                    if(true === $form->get('submitAndAdd')->isClicked()) {
                        //preset form with current item type
                        $previousItemType = $item->getItemType();
                        $item = new Item();
                        $item->setItemType($previousItemType);
                        $form = $this->createForm(ItemDefaultFormType::class, $item, [
                            'itemCategory' => $ic,
                            // 'preferedItemType' => $previousItemType
                        ]);
                        //redirect to form
                        return $this->render('app_item/create/default.html.twig', [
                            'controller_name' => 'AppItemController',
                            'form' => $form,
                            'itemCategory' => $ic,
                        ]);
                    }
                    //user doesn't want to add another => redirect to items
                    else {
                        return $this->redirectToRoute('app_items');
                    }
                }
                return $this->render('app_item/create/default.html.twig', [
                    'controller_name' => 'AppItemController',
                    'form' => $form,
                    'itemCategory' => $ic,
                ]);
                break;
        }
    }

    #[Route('/app/placard/{id}/modifier', name: 'app_item_update', requirements: ['id' => '\d+'])]
    public function updateItem(Request $request, EntityManagerInterface $em, int $id): Response
    {
        $item = $em->getRepository(Item::class)->find($id);
        $ic = $item->getItemType()->getCategory();

        if (!$item) {
            throw $this->createNotFoundException(
                'L\'objet n\'a pas été trouvé.'
            );
        }
        //prevent users from updating item they don't own
        if ($item->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException(
                'Vous n\'avez pas les droits pour modifier cet objet.'
            );
        }

        switch ($ic->getCode()) {
            case 'bibliotheque':
                $canEditInfo = true;
                if($item->getProperty4() !== null){
                    $canEditInfo = false;
                }
                $form = $this->createForm(ItemBookFormType::class, $item, [
                    'update_mode' => true,
                    'itemCategory' => $ic,
                    'canEditInfo' => $canEditInfo
                ]);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    
                    $em->persist($item);
                    $em->flush();
                    $this->addFlash('success', 'L\'objet a bien été mis à jour');
                    return $this->redirectToRoute('app_items');
                }
        
        
                return $this->render('app_item/update/book.html.twig', [
                    'controller_name' => 'AppCircleController',
                    'itemCategory' => $ic,
                    'item'=>$item,
                    'form' => $form,
                ]);
            break;
            default:
                $form = $this->createForm(ItemDefaultFormType::class, $item, [
                    'update_mode' => true,
                    'itemCategory' => $ic
                ]);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    
                    $em->persist($item);
                    $em->flush();
                    $this->addFlash('success', 'L\'objet a bien été mis à jour');
                    return $this->redirectToRoute('app_items');
                }
        
        
                return $this->render('app_item/update/default.html.twig', [
                    'controller_name' => 'AppCircleController',
                    'itemCategory' => $ic,
                    'item'=>$item,
                    'form' => $form,
                ]);
            break;
        }
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

    #[Route('/app/objet/{id}/marquer', name: 'app_item_bookmark', requirements: ['id' => '\d+'], options: ['expose' => true])]
    public function bookmarkItem(Request $request, EntityManagerInterface $em, int $id): Response
    {
        $item = $em->getRepository(Item::class)->find($id);
        if (!$item) {
            throw $this->createNotFoundException(
                'L\'objet n\'a pas été trouvé'
            );
        }
        $user = $this->getUser();
        // check not already favorite
        $favoriteItem = new UserFavoriteItem();
        $favoriteItem->setItemId($item);
        $favoriteItem->setCreatedAt(new DateTime('now'));
        $favoriteItem->setUserId($user);
        $em->persist($favoriteItem);
        $em->flush();
        return $this->json([]);
    }

    #[Route('/app/objet/{id}/demarquer', name: 'app_item_unbookmark', requirements: ['id' => '\d+'], options: ['expose' => true])]
    public function unbookmarkItem(Request $request, EntityManagerInterface $em, int $id): Response
    {
        $user = $this->getUser();
        $favoriteItem = $em->getRepository(UserFavoriteItem::class)->findOneByUserAndItem($user, $id);
        if (!$favoriteItem) {
            throw $this->createNotFoundException(
                'L\'objet favori n\'a pas été trouvé'
            );
        }
        //Delete
        $em->remove($favoriteItem);

        $em->flush();
        return $this->json([]);
    }
}
