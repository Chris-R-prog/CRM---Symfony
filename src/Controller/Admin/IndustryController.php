<?php

namespace App\Controller\Admin;

use App\Entity\Industry;
use App\Form\IndustryType;
use App\Repository\IndustryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/industry')]
final class IndustryController extends AbstractController
{
    #[Route(name: 'admin.industry', methods: ['GET'])]
    public function index(IndustryRepository $industryRepository): Response
    {
        return $this->render('admin/industry/index.html.twig', [
            'industries' => $industryRepository->findAllOrdered(),
        ]);
    }

    #[Route('/new', name: 'admin.industry.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $industry = new Industry();
        $form = $this->createForm(IndustryType::class, $industry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($industry);
            $entityManager->flush();

            return $this->redirectToRoute('admin.industry', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/industry/new.html.twig', [
            'industry' => $industry,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin.industry.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Industry $industry, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(IndustryType::class, $industry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin.industry', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/industry/edit.html.twig', [
            'industry' => $industry,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin.industry.delete', methods: ['POST'])]
    public function delete(Request $request, Industry $industry, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $industry->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($industry);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.industry', [], Response::HTTP_SEE_OTHER);
    }
}
