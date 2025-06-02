<?php

namespace App\Controller\Front;

use App\Entity\Back\Contribution;
use App\Form\Front\ContributionFrontType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BonnePratiqueController extends AbstractController
{
    #[Route('/Front/Bonne-pratique', name: 'app_front_bonne_pratique')]
    public function index(EntityManagerInterface $entityManager,Request $request,PaginatorInterface $paginator): Response {
        $query = $entityManager->getRepository(Contribution::class)
            ->createQueryBuilder('bp')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Page actuelle
            9 // Limite par page
        );

        return $this->render('Front/bonnePratique/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/Front/Bonne-pratique/{token}', name: 'app_front_bonne_pratique_show', methods: ['GET'])]
    public function show($token, EntityManagerInterface $entityManager): Response
    {
        $bonnePratique = $entityManager->getRepository(Contribution::class)->findOneByToken($token);
        // dump($bonnePratique);die();
        return $this->render('Front/bonnePratique/show.html.twig', [
            'bonnePratique' => $bonnePratique,
        ]);
    }

    #[Route('/Front/Bonne-pratique/new', name: 'app_front_contribution_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contribution = new Contribution();
        $form = $this->createForm(ContributionFrontType::class, $contribution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            $ext = null;
            if ($file) {
                $uploadsDirectory = $this->getParameter('kernel.project_dir') . '/ressources/imgContribution';
                $ext = $file->guessExtension();
                $newFilename = $contribution->getToken() . '.' . $file->guessExtension();

                try {
                    $file->move($uploadsDirectory, $newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'An error occurred while uploading the file.');
                }
            }
            $contribution->setFile($ext);
            $contribution->setCreatedBy($this->getUser());
            $entityManager->persist($contribution);
            $entityManager->flush();

            return $this->redirectToRoute('app_front_bonne_pratique', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Front/bonnePratique/new.html.twig', [
            'contribution' => $contribution,
            'form' => $form,
        ]);
    }
}