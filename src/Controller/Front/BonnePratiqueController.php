<?php

namespace App\Controller\Front;

use App\Entity\Back\Contribution;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BonnePratiqueController extends AbstractController
{
    #[Route('/Front/Bonne-pratique', name: 'app_front_bonne_pratique')]
    public function index(
        EntityManagerInterface $entityManager,
        Request $request,
        PaginatorInterface $paginator
    ): Response {
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
}