<?php

namespace App\Controller;

use App\Entity\AffectationSalon;
use App\Entity\Messages;
use App\Entity\Salons;
use App\Entity\UserUCO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    /**
     * @Route("/menu", name="menu")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager("SYSTEME_INFO");

        $user = $this->getUser();
        $username = $user->getUsername();
        $messages = $em->getRepository(Messages::class)->findBy(["idSalon" => 1],array("idMessages" => "asc"));

        $salonsId = $em->getRepository(AffectationSalon::class)->findBy(["idUser" => $user->getId()]);
        $listSalon = "";

        foreach ($salonsId as $elt){
            $listSalon .= $elt->getIdSalon().",";
        }

        $listSalon = substr($listSalon, 0, -1);

        $salons = $em->getRepository(Salons::class)->findBy(["idSalon" => explode(",",$listSalon)]);

        return $this->render('menu/index.html.twig', [
            'controller_name' => 'MenuController',
            'user' => $user,
            'id' => $user->getId(),
            'username' => $username,
            'salons' => $salons,
            'messages' => $messages,
        ]);
    }

}
