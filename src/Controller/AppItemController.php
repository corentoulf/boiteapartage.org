<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\ItemCategory;
use App\Entity\ItemCircle;
use App\Entity\ItemType;
use App\Entity\Loan;
use App\Entity\UserFavoriteItem;
use App\Form\ItemBookFormType;
use App\Form\ItemDefaultFormType;
use App\Form\LoanRequestFormType;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Workflow\WorkflowInterface;

class AppItemController extends AbstractController
{
    public function __construct(
        #[Target('loan_lifecycle')] private WorkflowInterface $workflow,
    ) {
    }
    #[Route('/app/mes-objets', name: 'app_items_mine')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $userItems = $em->getRepository(Item::class)->findBy(
            ['owner' => $user->getId()],
            ['id' => 'DESC']
        );
        $itemCategories = $em->getRepository(ItemCategory::class)->findAllSortByLabel();
        return $this->render('app_item/list_mine.html.twig', [
            'controller_name' => 'AppObjectController',
            'items' => $userItems,
            'itemCategories' => $itemCategories
        ]);
    }

    #[Route('/app/mes-objets/ajout', name: 'app_item_create')]
    public function createItem(Request $request, EntityManagerInterface $em): Response
    {
        return $this->render('app_item/create/index.html.twig', [
            'controller_name' => 'AppCircleController'
        ]);
    }

    #[Route('/app/mes-objets/ajout/{code}', name: 'app_item_create_by_category')]
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
                        return $this->redirectToRoute('app_items_mine');
                    }
                }
                return $this->render('app_item/create/book.html.twig', [
                    'controller_name' => 'AppItemController',
                    'form' => $form,
                ]);
            
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
                        return $this->redirectToRoute('app_items_mine');
                    }
                }
                return $this->render('app_item/create/default.html.twig', [
                    'controller_name' => 'AppItemController',
                    'form' => $form,
                    'itemCategory' => $ic,
                ]);
        }
    }

    #[Route('/app/objets/{id}/modifier', name: 'app_item_update', requirements: ['id' => '\d+'])]
    public function updateItem(Request $request, EntityManagerInterface $em, int $id): Response
    {
        $item = $em->getRepository(Item::class)->find($id);
        $ic = $item->getItemType()->getCategory();

        if (!$item instanceof \App\Entity\Item) {
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
                    return $this->redirectToRoute('app_items_mine');
                }
        
        
                return $this->render('app_item/update/book.html.twig', [
                    'controller_name' => 'AppCircleController',
                    'itemCategory' => $ic,
                    'item'=>$item,
                    'form' => $form,
                ]);
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
                    return $this->redirectToRoute('app_items_mine');
                }
        
        
                return $this->render('app_item/update/default.html.twig', [
                    'controller_name' => 'AppCircleController',
                    'itemCategory' => $ic,
                    'item'=>$item,
                    'form' => $form,
                ]);
        }
    }

    #[Route('/app/objets/{id}/supprimer', name: 'app_item_delete', requirements: ['id' => '\d+'])]
    public function deleteItem(Request $request, EntityManagerInterface $em, int $id): Response
    {
        $item = $em->getRepository(Item::class)->find($id);
        if (!$item instanceof \App\Entity\Item) {
            throw $this->createNotFoundException(
                'L\'objet n\'a pas été trouvé'
            );
        }
        //prevent users from updating item they don't own
        if ($item->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException(
                'Vous n\'avez pas les droits pour supprimer cet objet.'
            );
        }
        $em->remove($item);
        $em->flush();
        $this->addFlash('success', 'L\'objet a bien été supprimé');
        return $this->redirectToRoute('app_items_mine');
    }

    #[Route('/app/objets/{id}/marquer', name: 'app_item_bookmark', requirements: ['id' => '\d+'], options: ['expose' => true])]
    public function bookmarkItem(Request $request, EntityManagerInterface $em, int $id): Response
    {
        $item = $em->getRepository(Item::class)->find($id);
        if (!$item instanceof \App\Entity\Item) {
            throw $this->createNotFoundException(
                'L\'objet n\'a pas été trouvé'
            );
        }
        $user = $this->getUser();
        // check not already favorite
        $favoriteItem = new UserFavoriteItem();
        $favoriteItem->setItemId($item);
        $favoriteItem->setCreatedAt(new DateTime('now'));
        $favoriteItem->setUser($user);
        $em->persist($favoriteItem);
        $em->flush();
        return $this->json([]);
    }

    #[Route('/app/objets/{id}/demarquer', name: 'app_item_unbookmark', requirements: ['id' => '\d+'], options: ['expose' => true])]
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
    
    #[Route('/app/objets/favoris', name: 'app_user_favorite_items')]
    public function user_favorite(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $itemCategories = $em->getRepository(ItemCategory::class)->findAllSortByLabel();
        $favoriteItems = $user->getUserFavoriteItems();
        return $this->render('app_item/favorite/index.html.twig', [
            'controller_name' => 'AppUserProfileController',
            'itemCategories' => $itemCategories,
            'items' => $favoriteItems
        ]);
    }

    #[Route('/app/objets/{id}', name: 'app_item_request_loan', requirements: ['id' => '\d+'])]
    public function requestLoan(Request $request, EntityManagerInterface $em, int $id): Response
    {
        $user = $this->getUser();
        $item = $em->getRepository(Item::class)->findWithCirclesAndMembers($id);
       //check item exists
        if (!$item instanceof Item) {
            throw $this->createNotFoundException(
                'L\'objet n\'a pas été trouvé.'
                );
        }
        //check user has the rights to view item
        $this->denyAccessUnlessGranted('borrow', $item);
        
        $userFavoriteItems = $user->getUserFavoriteItems();
        //check that user doesn't already have a pending request for this item (requested, accepted)
        $loans = $em->getRepository(Loan::class)->findPendingByItemBorrower($item, $user);
        if(count($loans) > 0) {
            return $this->render('app_item/show/pending_loan.html.twig', [
            'controller_name' => 'AppUserProfileController',
            // 'item' => $item,
            'item' => $item,
            'userFavoriteItems' => $userFavoriteItems,
            'loans' => $loans
        ]);
        }

        $loan = new Loan();
        $loan->setItem($item);
        $loan->setRequestedStartDate(new DateTime('now'));
        $loan->setRequestedEndDate(new DateTime('now')->add(DateInterval::createFromDateString('7 day')));
        $this->workflow->getMarking($loan);
        $form = $this->createForm(LoanRequestFormType::class, $loan, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $loan->setCreatedAt(new DateTime('now'));
            $loan->setLender($item->getOwner());
            $loan->setBorrower($user);
            $em->persist($loan);
            $em->flush();
            return $this->redirectToRoute('app_item_request_loan', ['id'=> $item->getId()]);
        }
        
        return $this->render('app_item/show/index.html.twig', [
            'controller_name' => 'AppUserProfileController',
            // 'item' => $item,
            'item' => $item,
            'userFavoriteItems' => $userFavoriteItems,
            'form' => $form,
            'loans' => $loans
        ]);
    }

     #[Route('/app/objets/', name: 'app_items_search')]
    // #[IsGranted('browseAll', null, 'Vous n\'avez pas le droit de consulter les boîtes. Avez-vous vérifié votre email et partagé 5 objets ?')]
    public function search(
        Request $request, 
        EntityManagerInterface $em,
        #[MapQueryParameter] ?string $q,
        #[MapQueryParameter] ?string $cat,
    ): Response
    {
        $user = $this->getUser();
        $results = $em->getRepository(Item::class)->findByKeywordAndCategory(
            user:     $user,
            keyword:  $q,
            category:  $cat,
        );

        $userFavoriteItems = $user->getUserFavoriteItems();
        // $items = $em->getRepository(ItemCircle::class)->findBy(['circle'], $circlesToFetch);
        
        $itemCategories = $em->getRepository(ItemCategory::class)->findAllSortByLabel();
        return $this->render('app_home/search.html.twig', [
            'controller_name' => 'AppCircleController',
            'items' => $results,
            "searchTerms" => $q,
            "category" => $cat,
            'itemCategories' => $itemCategories,
            'userFavoriteItems' => $userFavoriteItems
        ]);
    }
}