<?php

namespace App\Controller;

use App\Entity\AffectationSalon;
use App\Entity\Messages;
use App\Entity\Salons;
use App\Entity\UserUCO;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SalonController extends AbstractController
{
    /**
     * @Route("/salon/{id}", name="salon")
     * @param $id
     * @return Response
     * @throws DBALException
     */
    public function index($id)
    {
        $em = $this->getDoctrine()->getManager("SYSTEME_INFO");
        $user = $this->getUser();

        $ifInSalon = $em->getRepository(AffectationSalon::class)->findOneBy(["idUser" => $user->getId(), "idSalon" => $id]);

        if($ifInSalon != []) {
            $username = $user->getUsername();
            $messages = $em->getRepository(Messages::class)->findBy(["idSalon" => $id], array("idMessages" => "asc"));

            $listeUser = $em->getRepository(UserUCO::class)->getAllUserInSalon($id);
            $listeUserOut = $em->getRepository(UserUCO::class)->getAllUserNotInSalon($id);

            $salonsId = $em->getRepository(AffectationSalon::class)->findBy(["idUser" => $user->getId()]);

            $listSalon = "";

            foreach ($salonsId as $elt) {
                $listSalon .= $elt->getIdSalon() . ",";
            }

            $listSalon = substr($listSalon, 0, -1);

            $salons = $em->getRepository(Salons::class)->findBy(["idSalon" => explode(",",$listSalon)]);
            $salon = $em->getRepository(Salons::class)->find($id);

        }else{
            return $this->redirectToRoute('menu');
        }

        return $this->render('salon/index.html.twig', [
            'controller_name' => 'SalonController',
            'user' => $user,
            'id' => $user->getId(),
            'username' => $username,
            'messages' => $messages,
            'salons' => $salons,
            'idSalon' => $id,
            'listeUserInSalon' => $listeUser,
            'listeUserNotInSalon' => $listeUserOut,
            'salon' => $salon,
            'ws_url' => '192.168.11.2:8080',
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
        $idSalon = $request->get("idSalon");

        $username = $user->getUsername();

        $message = new Messages();
        $message->setIdSalon($idSalon)
            ->setIdUser($user->getId())
            ->setMessage($msg)
            ->setUsername($username);

        $em->persist($message);
        $em->flush();

        $result = array("Username" => $username, "msg" => $msg);

        return new JsonResponse($result);
    }

    /**
     * @Route("/addUser", name="addUser")
     * @param Request $request
     * @return JsonResponse
     */
    public function addUser(Request $request){
        $idSalon = $request->get("idSalon");
        $idUser = $request->get("idUser");

        $em = $this->getDoctrine()->getManager("SYSTEME_INFO");
        $aff = new AffectationSalon();
        $aff->setIdSalon($idSalon)
            ->setIdUser($idUser);

        $em->persist($aff);
        $em->flush();

        return $this->json("ok");
    }

    /**
     * @Route("/delUser", name="delUser")
     * @param Request $request
     * @return JsonResponse
     */
    public function delUser(Request $request){
        $idSalon = $request->get("idSalon");
        $idUser = $request->get("idUser");

        $em = $this->getDoctrine()->getManager("SYSTEME_INFO");
        $aff = $em->getRepository(AffectationSalon::class)->findOneBy(["idSalon" => $idSalon, "idUser" => $idUser]);

        $em->remove($aff);
        $em->flush();

        return $this->json("ok");
    }

    /**
     * @Route("/addSalon", name="addSalon")
     * @param Request $request
     * @return JsonResponse
     */
    public function addSalon(Request $request){
        $nomSalon = $request->get("nomSalon");
        $icon = $request->get("icon");

        $em = $this->getDoctrine()->getManager("SYSTEME_INFO");
        $salon = new Salons();
        $salon->setIdCreateurSalon($this->getUser()->getId())
            ->setNameSalon($nomSalon)
            ->setIconSalon($icon);

        $em->persist($salon);
        $em->flush();

        $aff = new AffectationSalon();
        $aff->setIdUser($this->getUser()->getId())
            ->setIdSalon($salon->getIdSalon());

        $em->persist($aff);
        $em->flush();

        return $this->json($salon->getIdSalon());
    }

    /**
     * @Route("/delSalon", name="delSalon")
     * @param Request $request
     * @return JsonResponse
     */
    public function delSalon(Request $request){
        $idSalon = $request->get("idSalon");

        $em = $this->getDoctrine()->getManager("SYSTEME_INFO");

        $affs = $em->getRepository(AffectationSalon::class)->findBy(["idSalon" => $idSalon]);
        foreach ($affs as $aff){
            $em->remove($aff);
            $em->flush();
        }

        return $this->json("ok");
    }
}
