<?php

namespace App\Controller\Back;

use App\Entity\Back\Statut;
use App\Form\Back\StatutType;
use App\Repository\Back\StatutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/back/statut')]
final class StatutController extends AbstractController{
    #[Route(name: 'app_back_statut_index', methods: ['GET'])]
    public function index(StatutRepository $statutRepository): Response
    {
        return $this->render('Back/statut/index.html.twig', [
            'statuts' => $statutRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_back_statut_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $statut = new Statut();
        $form = $this->createForm(StatutType::class, $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($statut);
            $entityManager->flush();

            return $this->redirectToRoute('app_back_statut_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Back/statut/new.html.twig', [
            'statut' => $statut,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_statut_show', methods: ['GET'])]
    public function show(Statut $statut): Response
    {
        return $this->render('Back/statut/show.html.twig', [
            'statut' => $statut,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_back_statut_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Statut $statut, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StatutType::class, $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_back_statut_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Back/statut/edit.html.twig', [
            'statut' => $statut,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_statut_delete', methods: ['POST'])]
    public function delete(Request $request, Statut $statut, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$statut->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($statut);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_back_statut_index', [], Response::HTTP_SEE_OTHER);
    }
}
