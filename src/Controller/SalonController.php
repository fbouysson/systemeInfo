<?php

namespace App\Controller;

use App\Entity\AffectationSalon;
use App\Entity\Login;
use App\Entity\Messages;
use App\Entity\Salons;
use App\Entity\UserUCO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SalonController extends AbstractController
{
    /**
     * @Route("/salon/{id}", name="salon")
     * @param $id
     * @return Response
     */
    public function index($id)
    {
        $em = $this->getDoctrine()->getManager("SYSTEME_INFO");

        $user = $this->getUser();
        $username = $user->getUsername();
        $messages = $em->getRepository(Messages::class)->findBy(["idSalon" => $id],array("idMessages" => "asc"));

        $salonsId = $em->getRepository(AffectationSalon::class)->findBy(["idUser" => $user->getId()]);
        $listSalon = "";

        foreach ($salonsId as $elt){
            $listSalon .= $elt->getIdSalon().",";
        }

        $listSalon = substr($listSalon, 0, -1);

        $salons = $em->getRepository(Salons::class)->findBy(["idSalon" => [$listSalon]]);

        return $this->render('salon/index.html.twig', [
            'controller_name' => 'SalonController',
            'user' => $user,
            'id' => $user->getId(),
            'username' => $username,
            'messages' => $messages,
            'salons' => $salons,
        ]);
    }

    /**
     * @Route("/sendMsg", name="send_msg")
     * @param Request $request
     * @return JsonResponse
     */
    public function sendMsg(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager("SYSTEME_INFO");

        $msg = $request->get("msg");
        $username = $user->getUsername();

        $message = new Messages();
        $message->setIdSalon(1)
            ->setIdUser($user->getId())
            ->setMessage($msg)
            ->setUsername($username);

        $em->persist($message);
        $em->flush();

        $result = array("Username" => $username, "msg" => $msg);
        return new JsonResponse($result);
    }
}
