<?php

namespace App\Controller\Back;

use App\Entity\Back\Contribution;
use App\Form\Back\ContributionType;
use App\Repository\Back\ContributionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/back/contribution')]
final class ContributionController extends AbstractController{
    #[Route(name: 'app_back_contribution_index', methods: ['GET'])]
    public function index(ContributionRepository $contributionRepository): Response
    {
        return $this->render('Back/contribution/index.html.twig', [
            'contributions' => $contributionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_back_contribution_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contribution = new Contribution();
        $form = $this->createForm(ContributionType::class, $contribution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            $ext = null;
            if ($file) {
                $ext = $file->guessExtension();
                $newFilename = $contribution->getToken() . '.' . $ext;
                $pathUpload = $this->getParameter('kernel.project_dir') . '/public/ressources/imgContribution';

                try {
                    $file->move(
                        $pathUpload,
                        $newFilename
                    );
                } catch (FileException $e) {
                    error_log('Erreur upload : ' . $e->getMessage());
                }
            }
            $contribution->setFile($ext);
            $contribution->setCreatedBy($this->getUser());
            $entityManager->persist($contribution);
            $entityManager->flush();

            return $this->redirectToRoute('app_back_contribution_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Back/contribution/new.html.twig', [
            'contribution' => $contribution,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_contribution_show', methods: ['GET'])]
    public function show(Contribution $contribution): Response
    {
        return $this->render('Back/contribution/show.html.twig', [
            'contribution' => $contribution,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_back_contribution_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contribution $contribution, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContributionType::class, $contribution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            $ext = null;
            if ($file) {
                $ext = $file->guessExtension();
                $newFilename = $contribution->getToken() . '.' . $ext;
                $pathUpload = $this->getParameter('kernel.project_dir') . '/public/ressources/imgContribution';
                try {
                    $file->move(
                        $pathUpload,
                        $newFilename
                    );
                } catch (FileException $e) {
                    error_log('Erreur upload : ' . $e->getMessage());
                }
            }
            $contribution->setFile($ext);
            $entityManager->persist($contribution);
            $entityManager->flush();

            return $this->redirectToRoute('app_back_contribution_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Back/contribution/edit.html.twig', [
            'contribution' => $contribution,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_contribution_delete', methods: ['POST'])]
    public function delete(Request $request, Contribution $contribution, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contribution->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($contribution);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_back_contribution_index', [], Response::HTTP_SEE_OTHER);
    }
}
