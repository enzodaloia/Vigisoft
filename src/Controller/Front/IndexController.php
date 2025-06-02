<?php

namespace App\Controller\Front;

use App\Entity\Back\Tickets;
use App\Entity\Back\Statut;
use App\Entity\Back\Severite;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class IndexController extends AbstractController{
    #[Route('/Front/Dashboard', name: 'app_front_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $tickets = $entityManager->getRepository(Tickets::class)->findAll();
        $enCours = $entityManager->getRepository(Statut::class)->findOneBy(['titre' => 'En cours...']);
        $ticketsEnCours = $entityManager->getRepository(Tickets::class)->findBy(['statut' => $enCours->getId()]);

        // Tri des tickets par statut (1er camember)
        $statut = $entityManager->getRepository(Statut::class)->findAll();
        $statusTitre = [];
        $statusCounts = [];

        foreach ($statut as $statutItem) {
            $statusTitre[] = $statutItem->getTitre();
            $statusCounts[$statutItem->getTitre()] = 0;
        }

        foreach ($tickets as $ticket) {
            $statutTitre = $ticket->getStatut()->getTitre();
            if (isset($statusCounts[$statutTitre])) {
                $statusCounts[$statutTitre]++;
            }
        }

        $statusPercentages = [];
        $totalTickets = count($tickets);

        foreach ($statusCounts as $status => $count) {
            $percentage = $totalTickets > 0 ? round(($count / $totalTickets) * 100, 2) : 0;
            $statusPercentages[] = $percentage;
        }
        // Fin Tri des tickets par statut

        // Nombre de tickets résolus par mois
        $resolvedTicketsByMonth = array_fill(0, 12, 0);
        foreach ($tickets as $ticket) {
            if ($ticket->getStatut()->getTitre() === 'Clôturé') {
                $updatedAt = $ticket->getUpdatedAt();
                $month = (int) $updatedAt->format('n');
                $resolvedTicketsByMonth[$month - 1]++;
            }
        }
        // Fin Nombre de tickets résolus par mois

        // Tri des tickets par sévérité (2ème camembert)
        $severites = $entityManager->getRepository(Severite::class)->findAll();
        $severiteTitres = [];
        $severiteCounts = [];

        foreach ($severites as $severiteItem) {
            $titre = $severiteItem->getTitre();
            $severiteTitres[] = $titre;
            $severiteCounts[$titre] = 0;
        }

        foreach ($tickets as $ticket) {
            $severiteTitre = $ticket->getSeverite()->getTitre();
            if (isset($severiteCounts[$severiteTitre])) {
                $severiteCounts[$severiteTitre]++;
            }
        }

        $severitePercentages = [];
        $totalTickets = count($tickets);

        foreach ($severiteCounts as $titre => $count) {
            $percentage = $totalTickets > 0 ? round(($count / $totalTickets) * 100, 2) : 0;
            $severitePercentages[] = $percentage;
        }
        // Fin Tri des tickets par severite

        // Début Histogramme
        $monthlyTicketCount = array_fill(0, 12, 0);

        foreach ($tickets as $ticket) {
            $createdAt = $ticket->getCreatedAt();
            $month = (int) $createdAt->format('n');
            $monthlyTicketCount[$month - 1]++;
        }
        // Fin Histogramme

        // Tri des tickets par jour de la semaine
        $weeklyTicketCount = array_fill(0, 7, 0);

        foreach ($tickets as $ticket) {
            $dayOfWeek = (int) $ticket->getCreatedAt()->format('N');
            $weeklyTicketCount[$dayOfWeek - 1]++;
        }
        // FIN du Tri des tickets par jour de la semaine
        return $this->render('Front/index/index.html.twig', [
            'tickets' => $tickets,
            'monthlyTicketCount' => $monthlyTicketCount,
            'weeklyTicketCount' => $weeklyTicketCount,
            'ticketsEnCours' => count($ticketsEnCours),
            'statusTitre' => $statusTitre,
            'statusPercentages' => $statusPercentages,
            'severiteTitres' => $severiteTitres,
            'severitePercentages' => $severitePercentages,
            'resolvedTicketsByMonth' => $resolvedTicketsByMonth,
        ]);
    }
}
