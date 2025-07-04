<?php

namespace App\Controller\Back;

use App\Entity\Back\Tickets;
use App\Entity\Back\Statut;
use App\Form\Back\TicketsType;
use App\Repository\Back\SeveriteRepository;
use App\Repository\Back\StatutRepository;
use App\Repository\Back\TicketsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/back/tickets')]
final class TicketsController extends AbstractController{
    
    #[Route('/{id}/change-statut/{statutId}', name: 'app_back_tickets_change_statut', methods: ['GET'])]
    public function changeStatut(Tickets $ticket, int $statutId, EntityManagerInterface $entityManager): Response {
        $statut = $entityManager->getRepository(Statut::class)->find($statutId);

        $ticket->setStatut($statut);
        $ticket->setUpdatedAt(new \DateTimeImmutable());

        $entityManager->persist($ticket);
        $entityManager->flush();

        return $this->redirectToRoute('app_back_tickets_show', ['id' => $ticket->getId()]);
    }

    
    #[Route(name: 'app_back_tickets_index', methods: ['GET'])]
    public function index(TicketsRepository $ticketsRepository, StatutRepository $statutRepository, SeveriteRepository $severiteRepository): Response
    {
        return $this->render('Back/tickets/index.html.twig', [
            'tickets' => $ticketsRepository->findAll(),
            'statuts' => $statutRepository->findAll(),
            'severites' => $severiteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_back_tickets_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ticket = new Tickets();
        $form = $this->createForm(TicketsType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticket->setCreatedBy($this->getUser());
            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('app_back_tickets_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Back/tickets/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_tickets_show', methods: ['GET'])]
    public function show(Tickets $ticket, EntityManagerInterface $entityManager, ): Response
    {
        $statut = $entityManager->getRepository(Statut::class)->findAll();
        return $this->render('Back/tickets/show.html.twig', [
            'ticket' => $ticket,
            'statut' => $statut,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_back_tickets_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tickets $ticket, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TicketsType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticket->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('app_back_tickets_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Back/tickets/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_tickets_delete', methods: ['POST'])]
    public function delete(Request $request, Tickets $ticket, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($ticket);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_back_tickets_index', [], Response::HTTP_SEE_OTHER);
    }
}
