<?php

namespace App\Controller;

use App\Entity\OpportunityContact;
use App\Form\OpportunityContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OpportunityContactController extends AbstractController
{
    #[Route('/opportunity-contact/{id}/edit', name: 'opportunity_contact.edit', methods: ['GET', 'POST'])]
    public function edit(
        OpportunityContact $oc,
        Request $request,
        EntityManagerInterface $em
    ): Response {

        $form = $this->createForm(OpportunityContactType::class, $oc, [
            'account' => $oc->getOpportunity()->getAccount(),
            'opportunity' => $oc->getOpportunity(),
            'edit_mode' => true,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Contact modifié');

            return $this->redirectToRoute('opportunity.show', [
                'slug' => $oc->getOpportunity()->getSlug(),
            ]);
        }

        return $this->render('opportunity_contact/edit.html.twig', [
            'form' => $form,
            'oc' => $oc,
        ]);
    }

    #[Route('/opportunity-contact/{id}/delete', name: 'opportunity_contact.delete', methods: ['POST'])]
    public function delete(
        OpportunityContact $oc,
        Request $request,
        EntityManagerInterface $em
    ): Response {

        if ($this->isCsrfTokenValid('delete' . $oc->getId(), $request->request->get('_token'))) {
            $opportunity = $oc->getOpportunity();

            $em->remove($oc);
            $em->flush();

            $this->addFlash('success', 'Contact supprimé');
        }

        return $this->redirectToRoute('opportunity.show', [
            'slug' => $oc->getOpportunity()->getSlug(),
        ]);
    }
}
