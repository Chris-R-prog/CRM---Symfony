<?php

namespace App\Controller\Admin;

use App\Entity\ContactRole;
use App\Form\ContactRoleType;
use App\Repository\ContactRoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/contact_role')]
final class ContactRoleController extends AbstractController
{
    #[Route(name: 'admin.contact_role', methods: ['GET'])]
    public function index(ContactRoleRepository $contactRoleRepository): Response
    {
        return $this->render('admin/contact_role/index.html.twig', [
            'contact_roles' => $contactRoleRepository->findAllOrdered(),
        ]);
    }

    #[Route('/new', name: 'admin.contact_role.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contactRole = new ContactRole();
        $form = $this->createForm(ContactRoleType::class, $contactRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contactRole);
            $entityManager->flush();

            return $this->redirectToRoute('admin.contact_role', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/contact_role/new.html.twig', [
            'contact_role' => $contactRole,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin.contact_role.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ContactRole $contactRole, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContactRoleType::class, $contactRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin.contact_role', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/contact_role/edit.html.twig', [
            'contact_role' => $contactRole,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin.contact_role.delete', methods: ['POST'])]
    public function delete(Request $request, ContactRole $contactRole, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $contactRole->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($contactRole);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.contact_role', [], Response::HTTP_SEE_OTHER);
    }
}
