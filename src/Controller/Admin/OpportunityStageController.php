<?php

namespace App\Controller\Admin;

use App\Entity\OpportunityStage;
use App\Form\OpportunityStageType;
use App\Repository\OpportunityStageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/opportunity_stage')]
final class OpportunityStageController extends AbstractController
{
    #[Route(name: 'admin.opportunity_stage', methods: ['GET'])]
    public function index(OpportunityStageRepository $opportunityStageRepository): Response
    {
        return $this->render('admin/opportunity_stage/index.html.twig', [
            'opportunity_stages' => $opportunityStageRepository->findAllOrdered(),
        ]);
    }

    #[Route('/new', name: 'admin.opportunity_stage.new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $opportunityStage = new OpportunityStage();
        $form = $this->createForm(OpportunityStageType::class, $opportunityStage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($opportunityStage);
            $entityManager->flush();

            $this->addFlash('success', 'L\'étape de vente a bien été créée');

            return $this->redirectToRoute('admin.opportunity_stage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/opportunity_stage/new.html.twig', [
            'opportunity_stage' => $opportunityStage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin.opportunity_stage.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, OpportunityStage $opportunityStage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OpportunityStageType::class, $opportunityStage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'L\'étape de vente a bien été modifiée');

            return $this->redirectToRoute('admin.opportunity_stage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/opportunity_stage/edit.html.twig', [
            'opportunity_stage' => $opportunityStage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin.opportunity_stage.delete', methods: ['POST'])]
    public function delete(Request $request, OpportunityStage $opportunityStage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $opportunityStage->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($opportunityStage);
            $entityManager->flush();

            $this->addFlash('success', 'L\'étape de vente à bien été supprimée');
        }

        return $this->redirectToRoute('admin.opportunity_stage', [], Response::HTTP_SEE_OTHER);
    }
}
