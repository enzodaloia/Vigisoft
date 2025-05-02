<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController{
    #[Route('/Front/Dashboard', name: 'app_front_index')]
    public function index(): Response
    {
        return $this->render('Front/index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
