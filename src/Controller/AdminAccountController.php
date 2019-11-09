<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAccountController extends AbstractController
{
    /**
     * @Route("/admin/login", name="admin_account_login")
     */
    public function login()
    {
        return $this->render('admin/account/login.html.twig', [
            'controller_name' => 'AdminAccountController',
        ]);
    }
}
