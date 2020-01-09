<?php

namespace App\Controller;

use App\Entity\Login;
use App\Entity\Messages;
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

        $user = $_SESSION["user_data"];
        $username = $em->getRepository(Login::class)->findOneBy(["loginIdUser" => $user->getIdUser()])->getLoginUsername();
        $messages = $em->getRepository(Messages::class)->findBy(["idSalon" => $id],array("idMessages" => "asc"));

        return $this->render('salon/index.html.twig', [
            'controller_name' => 'SalonController',
            'user' => $user,
            'id' => $user->getIdUser(),
            'username' => $username,
            'messages' => $messages,
        ]);
    }

    /**
     * @Route("/sendMsg", name="send_msg")
     * @param Request $request
     * @return JsonResponse
     */
    public function sendMsg(Request $request)
    {
        $user = $_SESSION["user_data"];
        $em = $this->getDoctrine()->getManager("SYSTEME_INFO");

        $msg = $request->get("msg");
        $username = $em->getRepository(Login::class)->findOneBy(["loginIdUser" => $user->getIdUser()])->getLoginUsername();

        $message = new Messages();
        $message->setIdSalon(1)
            ->setIdUser($user->getIdUser())
            ->setMessage($msg)
            ->setUsername($username);

        $em->persist($message);
        $em->flush();

        $result = array("Username" => $username, "msg" => $msg);
        return new JsonResponse($result);
    }
}
