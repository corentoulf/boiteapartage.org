<?php

namespace App\Controller;

use App\Entity\Circle;
use App\Entity\ItemCircle;
use App\Entity\UserCircle;
use App\Form\CircleFormType;
use App\Model\Commune;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AppCircleController extends AbstractController
{

    #[Route('/app/boite', name: 'app_circle')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $userCircles = $em->getRepository(UserCircle::class)->findBy(['user_id' => $user->getId()]);

        return $this->render('app_circle/index.html.twig', [
            'controller_name' => 'AppCircleController',
            'userCircles' => $userCircles,
        ]);
    }

    

    #[Route('/app/boite/nouveau', name: 'app_circle_create')]
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

            $this->addFlash('success', 'La boîte a bien été créé.');
            return $this->redirectToRoute('app_auth_home');
        }


        return $this->render('app_circle/create.html.twig', [
            'controller_name' => 'AppCircleController',
            'form' => $form,
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

    #[Route('/app/boite/rejoindre', name: 'app_circle_join')]
    public function join(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        // creates a userCircle object and initializes user
        $userCircle = new UserCircle();
        $userCircle->setUserId($user);


        $form = $this->createFormBuilder($userCircle)
            ->add('circleIdentifier', TextType::class, [
                'label' => 'Identifiant de la boîte',
                'mapped' => false, 
                'attr' => [
                    'placeholder' => 'AA11BB',
                ],
                'help' => 'Il s\'agit de l\'dentifiant à 6 caractères présent sur le QR Code'])
            ->add('save', SubmitType::class, ['label' => 'Rejoindre la boîte'])
            ->getForm();
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //search for an existing circle with this identifier 
            $circleToFind = $form->get('circleIdentifier')->getData();
            $circle = $em->getRepository(Circle::class)->findOneBy(['short_id' => $circleToFind]);
            if(null !== $circle){
                //check if user is not already in the circle
                $existingUserInCircle = $em->getRepository(UserCircle::class)->findByUserAndCircleId($user, $circle);
                if(null == $existingUserInCircle){
                    $userCircle->setCircle($circle);
                    $userCircle->setCreatedAt(new DateTime('now'));
                    $em->persist($userCircle);
                    $em->flush();
                    $this->addFlash('success','Vous avez bien rejoint la boîte "'.$circle->getName().'"!');
                    return $this->redirectToRoute('app_auth_home');
                }
                else {
                    $this->addFlash('warning', 'Vous faites déjà partie de cette boîte, vous n\'avez pas besoin de le rejoindre de nouveau.');
                }
            }
            else {
                $this->addFlash('danger', 'Nous n\'avons pas trouvé de boîte avec cet identifiant. Êtes-vous sûr d\'avoir bien entré l\'identifiant ?');
            }

        }

        return $this->render('app_circle/join.html.twig', [
            'controller_name' => 'AppCircleController',
            'form' => $form,
            // 'short_id' => $shortId
        ]);
    }

    #[Route('/rejoindre-{shortId}', name: 'app_circle_join_identified')]
    public function joinIdentified(Request $request, EntityManagerInterface $em, string $shortId, Security $security): Response
    {
        //check if circle exists
        $circle = $em->getRepository(Circle::class)->findOneBy(['short_id' => $shortId]);
        if(null === $circle){
            throw $this->createNotFoundException(
                'Nous n\'avons pas trouvé la boîte associée à cet identifiant.'
            );
        }
        //if circle exists and user is auth => auto-join user to circle
        $user = $this->getUser();
        if ($security->isGranted('ROLE_USER')) {
            //check if user is not already in the circle
            $existingUserInCircle = $em->getRepository(UserCircle::class)->findByUserAndCircleId($user, $circle);
            if(null == $existingUserInCircle){
                // creates a userCircle object and initializes user
                $userCircle = new UserCircle();
                $userCircle->setUserId($user);
                $userCircle->setCircle($circle);
                $userCircle->setCreatedAt(new DateTime('now'));
                $em->persist($userCircle);
                $em->flush();
                $this->addFlash('success', 'Vous avez bien rejoint la boîte !');
                return $this->redirectToRoute('app_auth_home');
            }
            else {
                $this->addFlash('warning', 'Vous faites déjà partie de cette boîte !');
                return $this->redirectToRoute('app_auth_home');
            }
        }
        else {
            //user is not yet registered
            $session = $request->getSession();
            $session->set('registrationPurpose', 'joinCircleId');
            $session->set('registrationCircleId', $circle->getId());
            return $this->redirectToRoute('app_register');
        }
    }
    #[Route('/app/boite/{circle}', name: 'app_circle_show', requirements: ['circle' => '\d+'])]
    #[IsGranted('browse', 'circle', 'Vous n\'avez pas le droit de consulter cette boîte. Avez-vous vérifié votre email et partagé 5 objets ?')]
    public function showOne(Request $request, EntityManagerInterface $em, Circle $circle): Response
    {
        //fetch items that are in the $circle
        $items = $em->getRepository(ItemCircle::class)->findAllInArray(array($circle));

        return $this->render('app_circle/show.html.twig', [
            'controller_name' => 'AppCircleController',
            'items' => $items
        ]);
    }
    #[Route('/app/boite/all', name: 'app_circle_show_all')]
    #[IsGranted('browseAll', null, 'Vous n\'avez pas le droit de consulter les boîtes. Avez-vous vérifié votre email et partagé 5 objets ?')]
    public function showAll(Request $request, EntityManagerInterface $em): Response
    {

        $user = $this->getUser();
        $userCircles = $em->getRepository(UserCircle::class)->findBy(['user_id' => $user->getId()]);
        $searchTerms = $request->getPayload()->get('searchTerms');
        //get circles the user belongs to
        $circlesToFetch = array();
        foreach ($userCircles as $key => $userCircle) {
            array_push($circlesToFetch, $userCircle->getCircle()->getId());
        }
        //get all user circles items
        $items = $em->getRepository(ItemCircle::class)->findAllInArray($circlesToFetch);

        return $this->render('app_circle/show.html.twig', [
            'controller_name' => 'AppCircleController',
            'items' => $items,
            "searchTerms" => $searchTerms
        ]);
    }
}
