<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use App\Repository\ContactRepository;
use App\Repository\OpportunityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts')]
final class AccountController extends AbstractController
{
    #[Route(name: 'account.list', methods: ['GET'])]
    public function index(AccountRepository $accountRepository): Response
    {
        return $this->render('account/index.html.twig', [
            'accounts' => $accountRepository->findActive(),
        ]);
    }

    #[Route('/new', name: 'account.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($account);
            $entityManager->flush();
            $this->addFlash('success', sprintf('Le compte "%s" a bien été créé.', $account->getAccountName()));
            return $this->redirectToRoute('account.list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/new.html.twig', [
            'account' => $account,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'account.show', methods: ['GET'])]
    public function show(
        #[MapEntity(mapping: ['slug' => 'slug'])] Account $account,
        ContactRepository $contactRepository,
        OpportunityRepository $opportunityRepository
    ): Response {
        $contacts = $contactRepository->findActiveByAccount($account);
        $opportunities = $opportunityRepository->findActiveByAccount($account);

        return $this->render('account/show.html.twig', [
            'account' => $account,
            'contacts' => $contacts,
            'opportunities' => $opportunities,
        ]);
    }

    #[Route('/{slug}/edit', name: 'account.edit', methods: ['GET', 'POST'])]
    public function edit(#[MapEntity(mapping: ['slug' => 'slug'])] Account $account, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', sprintf('Le compte "%s" a bien été modifié.', $account->getAccountName()));
            return $this->redirectToRoute('account.list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/edit.html.twig', [
            'account' => $account,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}/delete', name: 'account.delete', methods: ['POST'])]
    public function delete(Request $request, #[MapEntity(mapping: ['slug' => 'slug'])] Account $account, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $account->getId(), $request->getPayload()->getString('_token'))) {
            $account->delete();
            $entityManager->flush();
            $this->addFlash('success', sprintf('Le compte "%s" a bien été supprimé.', $account->getAccountName()));
        }

        return $this->redirectToRoute('account.list', [], Response::HTTP_SEE_OTHER);
    }
}
