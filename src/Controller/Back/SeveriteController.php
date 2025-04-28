<?php

namespace App\Controller\Back;

use App\Entity\Back\Severite;
use App\Form\Back\SeveriteType;
use App\Repository\Back\SeveriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/back/severite')]
final class SeveriteController extends AbstractController{
    #[Route(name: 'app_back_severite_index', methods: ['GET'])]
    public function index(SeveriteRepository $severiteRepository): Response
    {
        return $this->render('back/severite/index.html.twig', [
            'severites' => $severiteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_back_severite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $severite = new Severite();
        $form = $this->createForm(SeveriteType::class, $severite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($severite);
            $entityManager->flush();

            return $this->redirectToRoute('app_back_severite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/severite/new.html.twig', [
            'severite' => $severite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_severite_show', methods: ['GET'])]
    public function show(Severite $severite): Response
    {
        return $this->render('back/severite/show.html.twig', [
            'severite' => $severite,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_back_severite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Severite $severite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SeveriteType::class, $severite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_back_severite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/severite/edit.html.twig', [
            'severite' => $severite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_severite_delete', methods: ['POST'])]
    public function delete(Request $request, Severite $severite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$severite->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($severite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_back_severite_index', [], Response::HTTP_SEE_OTHER);
    }
}
