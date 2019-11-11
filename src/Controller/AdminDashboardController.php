<?php

namespace App\Controller;

use App\Service\StatsService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashbard")
     */
    public function index(ObjectManager $manager, StatsService $statsService)
    {

        $stats = $statsService->getStats();
        
        $bestAds = $statsService->getBestAds();

        $WorstAds = $statsService->getWorstAds();

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats,
            'bestAds' => $bestAds,
            "worstAds" => $WorstAds
        ]);
    }
}
