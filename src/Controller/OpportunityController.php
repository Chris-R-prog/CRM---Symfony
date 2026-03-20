<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Opportunity;
use App\Entity\OpportunityContact;
use App\Entity\OpportunityLog;
use App\Form\OpportunityContactType;
use App\Form\OpportunityType;
use App\Repository\OpportunityRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
final class OpportunityController extends AbstractController
{
    #[Route('opportunity', name: 'opportunities.list', methods: ['GET'])]
    public function index(OpportunityRepository $opportunityRepository): Response
    {
        return $this->render('opportunity/index.html.twig', [
            'opportunities' => $opportunityRepository->findActive(),
        ]);
    }

    #[Route('/accounts/{slug}/opportunity/new', name: 'opportunity.new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        #[MapEntity(mapping: ['slug' => 'slug'])] Account $account,
    ): Response {
        $opportunity = new Opportunity();
        $opportunity->setAccount($account);

        $form = $this->createForm(OpportunityType::class, $opportunity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($opportunity);
            $entityManager->flush();

            $this->addFlash('success', 'L\'opportunité a bien été créée');

            return $this->redirectToRoute('account.show', [
                'slug' => $account->getSlug(),
            ]);
        }

        return $this->render('opportunity/new.html.twig', [
            'form' => $form,
            'account' => $account,
        ]);
    }

    #[Route('opportunity/{slug}', name: 'opportunity.show', methods: ['GET'])]
    public function show(
        #[MapEntity(mapping: ['slug' => 'slug'])] Opportunity $opportunity,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $opportunityContact = new OpportunityContact();
        $opportunityContact->setOpportunity($opportunity);

        $form = $this->createForm(OpportunityContactType::class, $opportunityContact, [
            'account' => $opportunity->getAccount(),
            'opportunity' => $opportunity,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $existing = $em->getRepository(OpportunityContact::class)
                ->findOneBy([
                    'opportunity' => $opportunity,
                    'contact' => $opportunityContact->getContact(),
                ]);

            if ($existing) {
                $this->addFlash('warning', 'Ce contact est déjà lié à l\'opportunité.');
            } else {
                $opportunity->addOpportunityContact($opportunityContact);
                $em->persist($opportunityContact);
                $em->flush();
                $this->addFlash('success', 'Contact ajouté à l\'opportunité.');
            }

            if (!$opportunityContact->getContact()) {
                $this->addFlash('error', 'Aucun contact sélectionné.');
                return $this->redirectToRoute('opportunity.show', [
                    'slug' => $opportunity->getSlug(),
                ]);
            }

            return $this->redirectToRoute('opportunity.show', [
                'slug' => $opportunity->getSlug(),
            ]);
        }

        return $this->render('opportunity/show.html.twig', [
            'opportunity' => $opportunity,
            'formOpportunityContact' => $form->createView(),
        ]);
    }

    #[Route('opportunity/{slug}/edit', name: 'opportunity.edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        #[MapEntity(mapping: ['slug' => 'slug'])] Opportunity $opportunity,
        EntityManagerInterface $entityManager,
    ): Response {


        $account = $opportunity->getAccount();
        $originalStage = $opportunity->getOpportunityStage();

        $form = $this->createForm(OpportunityType::class, $opportunity, [
            'account' => $account,
            'opportunity' => $opportunity,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($originalStage !== $opportunity->getOpportunityStage()) {

                $opportunityLog = new OpportunityLog();
                $opportunityLog->setOpportunity($opportunity);
                $opportunityLog->setStage($opportunity->getOpportunityStage());
                $opportunityLog->setChangedAt(new \DateTimeImmutable());
                $opportunityLog->setAmount($opportunity->getAmount());
                $opportunityLog->setEngagement($opportunity->getEngagement());

                $entityManager->persist($opportunityLog);
            }

            $entityManager->flush();

            return $this->redirectToRoute('account.show', [
                'slug' => $account->getSlug()
            ]);
        }

        return $this->render('opportunity/edit.html.twig', [
            'opportunity' => $opportunity,
            'form' => $form->createView(),
        ]);
    }

    #[Route('opportunity/{slug}/delete', name: 'opportunity.delete', methods: ['POST'])]
    public function delete(
        Request $request,
        #[MapEntity(mapping: ['slug' => 'slug'])] Opportunity $opportunity,
        EntityManagerInterface $entityManager
    ): Response {

        $account = $opportunity->getAccount();

        if ($this->isCsrfTokenValid(
            'delete' . $opportunity->getId(),
            $request->getPayload()->getString('_token')
        )) {
            $opportunity->delete();
            $entityManager->flush();

            $this->addFlash('success', 'L\'opportunité a bien été supprimée');
        }

        return $this->redirectToRoute('account.show', [
            'slug' => $account->getSlug()
        ]);
    }
}
