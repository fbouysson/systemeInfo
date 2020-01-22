<?php

namespace App\Controller;

use App\Entity\Login;
use App\Entity\Messages;
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
        $username = $em->getRepository(UserUCO::class)->findOneBy(["loginIdUser" => $user->getId()])->getLoginUsername();
        $messages = $em->getRepository(Messages::class)->findBy(["idSalon" => $id],array("idMessages" => "asc"));

        return $this->render('salon/index.html.twig', [
            'controller_name' => 'SalonController',
            'user' => $user,
            'id' => $user->getId(),
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
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager("SYSTEME_INFO");

        $msg = $request->get("msg");
        $username = $em->getRepository(UserUCO::class)->findOneBy(["loginIdUser" => $user->getId()])->getLoginUsername();

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
