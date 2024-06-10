<?php

namespace App\Controller;

use App\Entity\Circle;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\Switch_;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/inscription', name: 'app_register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        EntityManagerInterface $em,
        Security $security
        ): Response
    {
        //store purpose in session rather than keeping it in url bar.
        $registrationPurpose = $request->get('registrationPurpose');
        if($registrationPurpose){
            $request->getSession()->set('registrationPurpose', $registrationPurpose);
            return $this->redirectToRoute('app_register');
        }
        
        //get registrationPurpose from session
        $registrationPurpose = $request->getSession()->get('registrationPurpose');
        //if purpose is to join a specific circle, catch information about it
        $circle = null;
        if($registrationPurpose == "joinCircleId"){
            $circleToJoinId = $request->getSession()->get('registrationCircleId');
            $circle = $em->getRepository(Circle::class)->findOneBy(['id' => $circleToJoinId]);
        }
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setCreatedAt(new DateTime('now'));
            $em->persist($user);
            $em->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('corentoulf@gmail.com', 'La Boîte à Partage'))
                    ->to($user->getEmail())
                    ->subject('Merci de confirmer votre Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            // auto-log-in the user redirect to what he wanted to do (registrationPurpose)
            switch ($registrationPurpose) {
                case 'createCircle':
                    $security->login($user, 'form_login');
                    return $this->redirectToRoute('app_circle_create');
                    break;
                case 'joinCircle':
                    $security->login($user, 'form_login');
                    return $this->redirectToRoute('app_circle_join');
                    break;
                case 'joinCircleId':
                    $security->login($user, 'form_login');
                    return $this->redirectToRoute('app_circle_join_identified', ['shortId' => $circle->getShortId()]);
                    break;
                            
                default:
                    return $security->login($user, 'form_login'); //default success login path
                    break;
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
            'registrationPurpose' => $registrationPurpose,
            'registrationCircleId' => $circle
        ]);
    }

    #[Route('/verification/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Votre adresse mail a bien été vérifiée.');

        return $this->redirectToRoute('app_login');
    }
}
