<?php

namespace App\Controller;

use App\Entity\Circle;
use App\Entity\ItemCircle;
use App\Entity\UserCircle;
use App\Form\CircleFormType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AppCircleController extends AbstractController
{
    #[Route('/app/cercle', name: 'app_circle')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $userCircles = $em->getRepository(UserCircle::class)->findBy(['user_id' => $user->getId()]);
        
        return $this->render('app_circle/index.html.twig', [
            'controller_name' => 'AppCircleController',
            'userCircles' => $userCircles,
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

            $circles = [1,2];
            while (count($circles)>0) {
                //generate short code
                $shortId = $this->generate_short_id(6);
                //check if it doesn't already exists and loop untill true
                $circles = $em->getRepository(Circle::class)->findBy(['short_id' => $shortId]);
            }
            $circle->setShortId($shortId);
            $em->persist($circle);

            $userCircle = new UserCircle();
            $userCircle->setUserId($user);
            $userCircle->setCircle($circle);
            $userCircle->setCreatedAt(new DateTime('now'));
            $em->persist($userCircle);

            //add all user Items to the new Circle
            $userItems = $user->getItems();
            foreach ($userItems as &$userItem) {
                $itemCircle = new ItemCircle();
                $itemCircle->setCircle($circle); //populate circle
                $itemCircle->setItem($userItem); //populate item
                $em->persist($itemCircle); //persist
            }

            $em->flush();

            $this->addFlash('success', 'Le cercle a bien été créé.');
            return $this->redirectToRoute('app_circle');
        }


        return $this->render('app_circle/create.html.twig', [
            'controller_name' => 'AppCircleController',
            'form' => $form
        ]);
    }

    function generate_short_id ($len_of_gen_str){
        $chars = "ABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
        $var_size = strlen($chars);
        $short_code = "";
        for( $x = 0; $x < $len_of_gen_str; $x++ ) {
            $short_code .= $chars[ rand( 0, $var_size - 1 ) ];  
        }
        return $short_code;
    }

    #[Route('/app/cercle/rejoindre/{shortId?}', name: 'app_circle_join')]
    public function join(Request $request, EntityManagerInterface $em, ?string $shortId): Response
    {
        $user = $this->getUser();
        // creates a userCircle object and initializes user
        $userCircle = new UserCircle();
        $userCircle->setUserId($user);


        $form = $this->createFormBuilder($userCircle)
            ->add('circleIdentifier', TextType::class, ['label' => 'Identifiant du cercle', 'mapped' => false, 'help' => 'Il s\'agit de l\'dentifiant à 6 caractères présent sur le QR Code'])
            ->add('save', SubmitType::class, ['label' => 'Rejoindre le cercle'])
            ->getForm();
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //search for an existing circle with this identifier 
            $circleToFind = $form->get('circleIdentifier')->getData();
            $circle = $em->getRepository(Circle::class)->findOneBy(['short_id' => $circleToFind]);
            if(null !== $circle){
                //check if user is not already in the circle
                $existingUserInCircle = $em->getRepository(UserCircle::class)->findOneBy(['user_id' => $user]);
                if(null == $existingUserInCircle){
                    $userCircle->setCircle($circle);
                    $userCircle->setCreatedAt(new DateTime('now'));
                    $em->persist($userCircle);
                    $em->flush();
                    $this->addFlash('success', 'Vous avez bien rejoint le cercle !');
                    return $this->redirectToRoute('app_circle');
                }
                else {
                    $this->addFlash('info', 'Vous faites déjà partie de ce cercle, vous n\'avez pas besoin de le rejoindre de nouveau.');
                }
            }
            else {
                $this->addFlash('danger', 'Nous n\'avons pas trouvé de cercle avec cet identifiant. Êtes-vous sûr d\'avoir bien entré l\'identifiant ?');
            }

        }

        return $this->render('app_circle/join.html.twig', [
            'controller_name' => 'AppCircleController',
            'form' => $form,
            'short_id' => $shortId
        ]);
    }

    #[Route('/app/cercle/{id}', name: 'app_circle_show')]
    public function show(Request $request, EntityManagerInterface $em, string $id): Response
    {
        $user = $this->getUser();
        if($id == "all"){
            //get circles the user belongs to
            $circlesToFetch = array();
            $userCircles = $em->getRepository(UserCircle::class)->findBy(['user_id' => $user->getId()]);
            foreach ($userCircles as $key => $userCircle) {
                array_push($circlesToFetch, $userCircle->getCircle()->getId());
            }
            //get all user circles items
            $items = $em->getRepository(ItemCircle::class)->findAllInArray($circlesToFetch);

        }
        else {
            //get $id circle items
            $items = $em->getRepository(ItemCircle::class)->findAllInArray(array($id));
        }

        return $this->render('app_circle/show.html.twig', [
            'controller_name' => 'AppCircleController',
            'items' => $items
        ]);
    }
}
