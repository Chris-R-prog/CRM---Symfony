<?php

namespace App\Controller;

use App\Entity\Lead;
use App\Entity\User;
use App\Form\LeadType;
use App\Repository\LeadRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
final class LeadController extends AbstractController
{
    #[Route('/leads', name: 'leads.list', methods: ['GET'])]
    public function index(
        LeadRepository $leadRepository,
        Security $security,
    ): Response {
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('lead/index.html.twig', [
            'leads' => $leadRepository->findActiveByUser($user),
        ]);
    }

    #[Route('/lead/new', name: 'lead.new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        Security $security
    ): Response {
        $lead = new Lead();
        $lead->setUser($security->getUser());
        $form = $this->createForm(LeadType::class, $lead);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lead);
            $entityManager->flush();

            $this->addFlash('success', 'Le lead a bien été créé');

            return $this->redirectToRoute('leads.list', [
                'slug' => $lead->getSlug(),
            ]);
        }

        return $this->render('lead/new.html.twig', [
            'form' => $form->createView(),
            'lead' => $lead,
        ]);
    }

    #[Route('lead/{slug}', name: 'lead.show', methods: ['GET'])]
    public function show(
        #[MapEntity(mapping: ['slug' => 'slug'])] Lead $lead,
        Request $request,
        EntityManagerInterface $em
    ): Response {

        return $this->render('lead/show.html.twig', [
            'lead' => $lead,
        ]);
    }

    #[Route('/lead/{slug}/edit', name: 'lead.edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        #[MapEntity(mapping: ['slug' => 'slug'])] Lead $lead,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(LeadType::class, $lead);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le lead a bien été mis à jour');

            return $this->redirectToRoute('leads.list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lead/edit.html.twig', [
            'lead' => $lead,
            'form' => $form->createView(),
        ]);
    }

    #[Route('lead/{slug}/delete', name: 'lead.delete', methods: ['POST'])]
    public function delete(
        Request $request,
        #[MapEntity(mapping: ['slug' => 'slug'])] Lead $lead,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $lead->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($lead);
            $entityManager->flush();

            $this->addFlash('success', 'Le lead a bien été supprimé');
        }

        return $this->redirectToRoute('app_lead_index', [], Response::HTTP_SEE_OTHER);
    }
}
