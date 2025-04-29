<?php

namespace App\Controller\Front;

use App\Entity\Back\Contribution;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BonnePratiqueController extends AbstractController{
    #[Route('/Bonne-pratique', name: 'app_front_bonne_pratique')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $bp = $entityManager->getRepository(Contribution::class)->findAll();
        return $this->render('front/bonnePratique/index.html.twig', [
            'bonnePratique' => $bp,
        ]);
    }
}
