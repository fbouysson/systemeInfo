<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    /**
     * @Route("/menu", name="menu")
     */
    public function index()
    {
        $user = $_SESSION["user_data"];
        return $this->render('menu/index.html.twig', [
            'controller_name' => 'MenuController',
            'user' => $user,
        ]);
    }
}
