<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\UpdateUserInfoFormType;
use App\Form\UpdateUserPasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\SecurityBundle\Security;

class AppUserProfileController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/app/utilisateur/compte', name: 'app_user_profile')]
    public function index(): Response
    {
        return $this->render('app_user_profile/index.html.twig', [
            'controller_name' => 'AppUserProfileController',
        ]);
    }
    #[Route('/app/utilisateur/compte/mettre-a-jour', name: 'app_user_update_profile')]
    public function update_profile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $userCurrentEmail = $user->getEmail();
        $form = $this->createForm(UpdateUserInfoFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            #in case the user changes the email
            $formData = $form->getData();
            if($formData->getEmail() !== $userCurrentEmail){
                // generate a signed url and email it to the user
                $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('corentoulf@gmail.com', 'La Boîte à Partage'))
                    ->to($user->getEmail())
                    ->subject('Merci de confirmer votre Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
                );
                $user->setVerified(false);
                $this->addFlash('info', 'Merci de valider votre nouvelle adresse mail (un email vous a été envoyé)');
            }
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Vos informations ont bien été modifiées.');

            
            return $this->redirectToRoute('app_user_profile');
        }
        
        return $this->render('app_user_profile/update_info.html.twig', [
            'controller_name' => 'AppUserProfileController',
            'updateUserInfoForm' => $form
        ]);
    }

    #[Route('/app/utilisateur/compte/changer-mot-de-passe', name: 'app_user_update_password')]
    public function update_password(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        
        $form = $this->createForm(UpdateUserPasswordFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            if (!$userPasswordHasher->isPasswordValid($user, $form->get('currentPassword')->getData())) {
                $this->addFlash('danger', 'Le mot de passe actuel n\'est pas correct.');
                return $this->redirectToRoute('app_user_update_password');
            }
            else {

                // encode the plain password
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('newPassword')->getData()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Votre mot de passe a bien été modifié.');
                return $this->redirectToRoute('app_user_profile');
            }
        }
        
        return $this->render('app_user_profile/update_password.html.twig', [
            'controller_name' => 'AppUserProfileController',
            'updateUserPasswordForm' => $form
        ]);
    }

    #[Route('/app/utilisateur/compte/supprimer-le-compte', name: 'app_user_delete_account')]
    public function delete_account(): Response
    {
        return $this->render('app_user_profile/delete_account.html.twig', [
            'controller_name' => 'AppUserProfileController'
        ]);
    }

    #[Route('/app/utilisateur/compte/supprimer-le-compte/confirmer', name: 'app_user_delete_account_confirm')]
    public function delete_account_confirm(Security $security): Response
    {
        //TODO: delete the user and Its data
        $response = $security->logout(false);
        return $this->redirectToRoute('app_home');
    }
}
