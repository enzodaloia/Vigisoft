<?php
namespace App\Controller\Front;

use App\Service\CertRssService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class VeilleController extends AbstractController
{
    #[Route('/Front/veille/cert', name: 'app_front_veille_cert')]
    public function index(CertRssService $certRssService, Request $request, PaginatorInterface $paginator): Response
    {
        $alerts = $certRssService->getAlerts();
        
        usort($alerts, function ($a, $b) {
            return $b['publishedAt'] <=> $a['publishedAt'];
        });

        $pagination = $paginator->paginate(
            $alerts,
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('Front/veille/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}