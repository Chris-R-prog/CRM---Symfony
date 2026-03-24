<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
final class ContactController extends AbstractController
{
    #[Route('/contacts', name: 'contact.list', methods: ['GET'])]
    public function index(ContactRepository $contactRepository): Response
    {
        return $this->render('contact/index.html.twig', [
            'contacts' => $contactRepository->findActive(),
        ]);
    }

    #[Route('/accounts/{slug}/contacts/new', name: 'contact.new', methods: ['GET', 'POST'])]
    public function new(
        #[MapEntity(mapping: ['slug' => 'slug'])]
        Account $account,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $contact = new Contact();
        $contact->setAccount($account);
        $contact->setAddressline1($account->getAddressLine1());
        $contact->setAddressline2($account->getAddressLine2());
        $contact->setCity($account->getCity());
        $contact->setPostalcode($account->getPostalCode());
        $contact->setCountry($account->getCountry());

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();

            $this->addFlash('success', 'Le contact a bien été créé');

            return $this->redirectToRoute('account.show', [
                'slug' => $account->getSlug(),
            ]);
        }

        return $this->render('contact/new.html.twig', [
            'form' => $form->createView(),
            'account' => $account,
        ]);
    }

    #[Route('contacts/{slug}', name: 'contact.show', methods: ['GET'])]
    public function show(
        #[MapEntity(mapping: ['slug' => 'slug'])] Contact $contact
    ): Response {
        return $this->render('contact/show.html.twig', [
            'contact' => $contact,
        ]);
    }

    #[Route('contact/{slug}/edit', name: 'contact.edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        #[MapEntity(mapping: ['slug' => 'slug'])] Contact $contact,
        EntityManagerInterface $entityManager
    ): Response {

        $account = $contact->getAccount();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le contact a bien été modifié.');

            return $this->redirectToRoute('account.show', [
                'slug' => $account->getSlug()
            ]);
        }

        return $this->render('contact/edit.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }

    #[Route('contact/{slug}/delete', name: 'contact.delete', methods: ['POST'])]
    public function delete(
        Request $request,
        #[MapEntity(mapping: ['slug' => 'slug'])] Contact $contact,
        EntityManagerInterface $entityManager
    ): Response {
        $account = $contact->getAccount();

        if ($this->isCsrfTokenValid(
            'delete' . $contact->getId(),
            $request->getPayload()->getString('_token')
        )) {
            $contact->delete();
            $entityManager->flush();

            $this->addFlash('success', 'Le contact a bien été supprimé');
        }

        return $this->redirectToRoute('account.show', [
            'slug' => $account->getSlug()
        ]);
    }
}
