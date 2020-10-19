<?php

namespace App\Controller;

use App\Entity\AffectationSalon;
use App\Entity\CompteStatut;
use App\Entity\Logs;
use App\Entity\Messages;
use App\Entity\Salons;
use DateTime;
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

        if($user->getUsername() == "user"){
            $statut = $em->getRepository(CompteStatut::class)->findOneBy(['username' => $user->getUsername()]);

            if($statut == null || !$statut->getCompteStatut()){
                return $this->render('validation/validation.html.twig', [
                    'message' => "Veuiller valider votre adress mail !",
                ]);
            }
        }

        $username = $user->getUsername();

        $messages = $em->getRepository(Messages::class)->findBy(["idSalon" => 1],array("idMessages" => "asc"));

        $salonsId = $em->getRepository(AffectationSalon::class)->findBy(["idUser" => $user->getId()]);
        $listSalon = "";

        foreach ($salonsId as $elt){
            $listSalon .= $elt->getIdSalon().",";
        }

        $listSalon = substr($listSalon, 0, -1);

        $salons = $em->getRepository(Salons::class)->findBy(["id" => explode(",",$listSalon)]);

        $tabSalonId = array();
        foreach ($salons as $sal ){
            $tabSalonId[] = $sal->getIdSalon();
        }

        $log = $em->getRepository(Logs::class)->findOneBy(['salon' => null , "action" => $username, "statut" => 0], ["datetime" => "desc"]);
        $dateLog = $log->getDatetime();
        $now = new DateTime();

        $sendWelcome = false;
        if($dateLog->diff($now)->s <= 3){
            $sendWelcome = true;
        }

        return $this->render('menu/index.html.twig', [
            'controller_name' => 'MenuController',
            'user' => $user,
            'id' => $user->getId(),
            'username' => $username,
            'salons' => $salons,
            'tabSalonId' => $tabSalonId,
            'messages' => $messages,
            'sendWelcome' => $sendWelcome,

        ]);
    }

}
