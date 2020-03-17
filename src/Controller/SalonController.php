<?php

namespace App\Controller;

use App\Entity\AffectationSalon;
use App\Entity\Logs;
use App\Entity\Messages;
use App\Entity\Salons;
use App\Entity\UserUCO;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SalonController extends AbstractController
{
    /**
     * @Route("/salon/{id}", name="salon")
     * @param $id
     * @param Connection $connection
     * @return Response
     * @throws DBALException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function index($id, Connection $connection)
    {
        $em = $this->getDoctrine()->getManager("SYSTEME_INFO");
        $user = $this->getUser();

        $ifInSalon = $em->getRepository(AffectationSalon::class)->findOneBy(["idUser" => $user->getId(), "idSalon" => $id]);

        if ($ifInSalon != []) {
            $username = $user->getUsername();
            $messages = $em->getRepository(Messages::class)->findBy(["idSalon" => $id], array("idMessages" => "asc"));

            $listeUserTmp = $em->getRepository(UserUCO::class)->getAllUserInSalon($id);

            $listeUser = array();

            for ($i = 0; $i < sizeof($listeUserTmp); $i++) {
                $ifIsCo = $this->getDoctrine()->getRepository(Logs::class)->findOneBy(["action" => $listeUserTmp[$i]["username"], "salon" => null], ["datetime" => "desc"]);

                $tmp["id_user"] = $listeUserTmp[$i]["id"];
                $tmp["login_username"] = $listeUserTmp[$i]["username"];

                if ($ifIsCo == [] || $ifIsCo == null || $ifIsCo->getStatut() == 1) {
                    $tmp["statut"] = null;
                } else {
                    $lg = $this->getDoctrine()->getRepository(Logs::class)->findOneBy(["action" => $listeUserTmp[$i]["username"], "salon" => $id], ["datetime" => "desc"]);
                    if ($lg != null && $lg != []) {
                        $lg = $lg->getStatut();

                        $tmp["statut"] = $lg;
                    } else {
                        $tmp["statut"] = 1;
                    }
                }

                $listeUser[] = $tmp;
            }

            $listeUserOut = $em->getRepository(UserUCO::class)->getAllUserNotInSalon($id);

            $salonsId = $em->getRepository(AffectationSalon::class)->findBy(["idUser" => $user->getId()]);

            $listSalon = "";

            foreach ($salonsId as $elt) {
                $listSalon .= $elt->getIdSalon() . ",";
            }

            $listSalon = substr($listSalon, 0, -1);

            $salons = $em->getRepository(Salons::class)->findBy(["id" => explode(",", $listSalon)]);
            $salon = $em->getRepository(Salons::class)->find($id);

        } else {
            return $this->redirectToRoute('menu');
        }

        $lastMessage = $this->getDoctrine()->getRepository(UserUCO::class)->getLastMessageSeen($id,$username);
        $this->getDoctrine()->getRepository(Logs::class)->createLog($username, $id, 0);

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
            'lastMessage' => $lastMessage,
            'ws_url' => '127.0.0.1:8080',
        ]);
    }

    /**
     * @Route("/sendMsg", name="send_msg")
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
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
            ->setType('msg')
            ->setUsername($username);

        $em->persist($message);
        $em->flush();

        $result = array("Username" => $username, "msg" => $msg);

        return $this->json($result, 200);
    }

    /**
     * @Route("/sendImg/{salon}", name="send_img")
     * @param Request $request
     * @param $salon
     * @return JsonResponse
     */
    public function addImg(Request $request, $salon)
    {
        $img = new File($request->files->get("file"));

        $filesystem = new Filesystem();

        if (!$filesystem->exists("Uploads/img" . $salon)) {
            $filesystem->mkdir("Uploads/img" . $salon, 0777);
        }

        $filename = time() . ".jpeg";
        $img->move("Uploads/img" . $salon, $filename);

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager("SYSTEME_INFO");

        $username = $user->getUsername();

        $message = new Messages();
        $message->setIdSalon($salon)
            ->setIdUser($user->getId())
            ->setMessage($filename)
            ->setType('img')
            ->setUsername($username);

        $em->persist($message);
        $em->flush();

        return $this->json($filename, 200);
    }


    /**
     * @Route("/addUser", name="addUser")
     * @param Request $request
     * @return JsonResponse
     */
    public function addUser(Request $request)
    {
        $idSalon = $request->get("idSalon");
        $idUser = $request->get("idUser");

        $em = $this->getDoctrine()->getManager("SYSTEME_INFO");
        $aff = new AffectationSalon();
        $aff->setIdSalon($idSalon)
            ->setIdUser($idUser);

        $em->persist($aff);
        $em->flush();

        return $this->json("ok", 200);
    }

    /**
     * @Route("/delUser", name="delUser")
     * @param Request $request
     * @return JsonResponse
     */
    public function delUser(Request $request)
    {
        $idSalon = $request->get("idSalon");
        $idUser = $request->get("idUser");

        $em = $this->getDoctrine()->getManager("SYSTEME_INFO");
        $aff = $em->getRepository(AffectationSalon::class)->findOneBy(["idSalon" => $idSalon, "idUser" => $idUser]);

        $em->remove($aff);
        $em->flush();

        return $this->json("ok", 200);
    }

    /**
     * @Route("/addSalon", name="addSalon")
     * @param Request $request
     * @return JsonResponse
     */
    public function addSalon(Request $request)
    {
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

        return $this->json($salon->getIdSalon(), 200);
    }

    /**
     * @Route("/delSalon", name="delSalon")
     * @param Request $request
     * @return JsonResponse
     */
    public function delSalon(Request $request)
    {
        $idSalon = $request->get("idSalon");

        $em = $this->getDoctrine()->getManager("SYSTEME_INFO");

        $affs = $em->getRepository(AffectationSalon::class)->findBy(["idSalon" => $idSalon]);
        foreach ($affs as $aff) {
            $em->remove($aff);
            $em->flush();
        }

        return $this->json("ok", 200);
    }

    /**
     * @Route("/exitSalon", name="exitsalon")
     * @param Request $request
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function exitSalon(Request $request)
    {
        $this->getDoctrine()->getRepository(Logs::class)->createLog($request->get("username"), $request->get("id"), 1);
        return $this->json("ok", 200);
    }

}
