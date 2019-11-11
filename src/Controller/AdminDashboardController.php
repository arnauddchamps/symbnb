<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashbard")
     */
    public function index()
    {
        return $this->render('admin/dashboard/index.html.twig', [
        ]);
    }
}
