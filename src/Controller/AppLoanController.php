<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Loan;
use App\Security\Voter\LoanVoter;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Workflow\WorkflowInterface;

class AppLoanController extends AbstractController
{
    public function __construct(
        #[Target('loan_lifecycle')] private WorkflowInterface $workflow,
    ) {
    }
    #[Route('/app/emprunts/', name: 'app_list_incoming_loan')]
    public function listIncomingLoan(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $loans = $em->getRepository(Loan::class)->findLoanBorrowedWithoutHistory(['borrower' => $user]);
        $loansGroupedByStatus = [
            "requested" => [],
            "accepted" => [],
            "ongoing" => [],
            "past" => [],
        ];
        foreach ($loans as $loan) {
            $loanStatus = $loan->getStatus();
            if (!isset($loansGroupedByStatus[$loanStatus])) {
                $loansGroupedByStatus["past"][] = $loan;
            }
            else {
                $loansGroupedByStatus[$loanStatus][] = $loan;
            }
        }

        return $this->render('app_loan/incoming/list.html.twig', [
            'controller_name' => 'AppLoanController',
            'loans' => $loans,
            'loansGrouped' => $loansGroupedByStatus
        ]);
    }
     #[Route('/app/prets/', name: 'app_list_outgoing_loan')]
    public function listOutgoingLoan(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        // $userFavoriteItems = $user->getUserFavoriteItems();
        $loans = $em->getRepository(Loan::class)->findLoanLendedWithoutHistory(['lender' => $user]);
        $loansGroupedByStatus = [
            "requested" => [],
            "accepted" => [],
            "ongoing" => [],
            "past" => [],
        ];
        foreach ($loans as $loan) {
            $loanStatus = $loan->getStatus();
            if (!isset($loansGroupedByStatus[$loanStatus])) {
                $loansGroupedByStatus["past"][] = $loan;
            }
            else {
                $loansGroupedByStatus[$loanStatus][] = $loan;
            }
        }
        return $this->render('app_loan/outgoing/list.html.twig', [
            'controller_name' => 'AppLoanController',
            'loans' => $loans,
            'loansGrouped' => $loansGroupedByStatus
        ]);
    }

     #[Route('/app/prets-et-emprunts/', name: 'app_list_loan')]
    public function listLoan(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        // $userFavoriteItems = $user->getUserFavoriteItems();
        $loans = $em->getRepository(Loan::class)->findBy(['borrower' => $user]);
        return $this->render('app_loan/incoming/list.html.twig', [
            'controller_name' => 'AppLoanController',
            'loans' => $loans,
        ]);
    }

    #[Route('/app/emprunts/{id}', name: 'app_show_incoming_loan')]
    public function showIncomingLoan(EntityManagerInterface $em,  int $id): Response
    {
        $user = $this->getUser();
        $userFavoriteItems = $user->getUserFavoriteItems();
        $loan = $em->getRepository(Loan::class)->find($id);
        $this->denyAccessUnlessGranted(LoanVoter::BORROW, $loan);
        return $this->render('app_loan/incoming/show.html.twig', [
            'controller_name' => 'AppLoanController',
            'loan' => $loan,
        ]);
    }

   #[Route('/app/emprunts/{id}/annuler', name: 'app_cancel_incoming_loan')]
    public function cancelIncomingLoan(Request $request, EntityManagerInterface $em,  int $id): Response
    {
        $user = $this->getUser();
        $loan = $em->getRepository(Loan::class)->find($id);
        $this->denyAccessUnlessGranted(LoanVoter::CANCEL, $loan);
        if (!$this->isCsrfTokenValid('cancel_loan_' . $loan->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }
        try {
            // update the currentState on the post
            $this->workflow->apply($loan, 'cancel');
            $loan->setCancelledAt(new \DateTime('now'));
            $em->persist($loan);
            $em->flush();
        } catch (LogicException $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }
        return $this->redirectToRoute('app_show_incoming_loan', ['id' => $loan->getId()],);
    }

    #[Route('/app/prets/{id}', name: 'app_show_outgoing_loan')]
    public function showOutgoingLoan(EntityManagerInterface $em,  int $id): Response
    {
        $user = $this->getUser();
        $loan = $em->getRepository(Loan::class)->find($id);
        $this->denyAccessUnlessGranted(LoanVoter::LEND, $loan);
        return $this->render('app_loan/outgoing/show.html.twig', [
            'controller_name' => 'AppLoanController',
            'loan' => $loan,
        ]);
    }

    #[Route('/app/emprunts/{id}/accepter', name: 'app_accept_outgoing_loan')]
    public function acceptOutgoingLoan(Request $request, EntityManagerInterface $em,  int $id): Response
    {
        $user = $this->getUser();
        $loan = $em->getRepository(Loan::class)->find($id);
        $this->denyAccessUnlessGranted(LoanVoter::ACCEPT_OR_REJECT, $loan);
        if (!$this->isCsrfTokenValid('accept_loan_' . $loan->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }
        try {
            // update the currentState on the post
            $this->workflow->apply($loan, 'accept');
            $loan->setAcceptedAt(new \DateTime('now'));
            $em->persist($loan);
            $em->flush();
        } catch (LogicException $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }
        return $this->redirectToRoute('app_show_outgoing_loan', ['id' => $loan->getId()]);
    }
    #[Route('/app/emprunts/{id}/refuser', name: 'app_reject_outgoing_loan')]
    public function rejectOutgoingLoan(Request $request, EntityManagerInterface $em,  int $id): Response
    {
        $user = $this->getUser();
        $loan = $em->getRepository(Loan::class)->find($id);
        $this->denyAccessUnlessGranted(LoanVoter::ACCEPT_OR_REJECT, $loan);
        if (!$this->isCsrfTokenValid('reject_loan_' . $loan->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }
        try {
            // update the currentState on the post
            $this->workflow->apply($loan, 'reject');
            $loan->setRejectedAt(new \DateTime('now'));
            $em->persist($loan);
            $em->flush();
        } catch (LogicException $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }
        return $this->redirectToRoute('app_show_outgoing_loan', ['id' => $loan->getId()]);
    }

    #[Route('/app/emprunts/{id}/confirmer-la-remise', name: 'app_confirm_outgoing_loan_handover')]
    public function confirmOutgoingHandoverLoan(Request $request, EntityManagerInterface $em,  int $id): Response
    {
        $user = $this->getUser();
        $loan = $em->getRepository(Loan::class)->find($id);
        $this->denyAccessUnlessGranted(LoanVoter::CONFIRM_HANDOVER, $loan);
        if (!$this->isCsrfTokenValid('confirm_handover_loan_' . $loan->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }
        try {
            // update the currentState on the post
            $this->workflow->apply($loan, 'handover');
            $loan->setLentAt(new \DateTime('now'));
            $em->persist($loan);
            $em->flush();
        } catch (LogicException $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }
        return $this->redirectToRoute('app_show_outgoing_loan', ['id' => $loan->getId()]);
    }

    #[Route('/app/emprunts/{id}/confirmer-le-retour', name: 'app_confirm_outgoing_loan_return')]
    public function confirmOutgoingReturnLoan(Request $request, EntityManagerInterface $em,  int $id): Response
    {
        $user = $this->getUser();
        $loan = $em->getRepository(Loan::class)->find($id);
        $this->denyAccessUnlessGranted(LoanVoter::CONFIRM_RETURN, $loan);
        if (!$this->isCsrfTokenValid('confirm_return_loan_' . $loan->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }
        try {
            // update the currentState on the post
            $this->workflow->apply($loan, 'return');
            $loan->setReturnedAt(new \DateTime('now'));
            $em->persist($loan);
            $em->flush();
        } catch (LogicException $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }
        return $this->redirectToRoute('app_show_outgoing_loan', ['id' => $loan->getId()]);
    }
    
}