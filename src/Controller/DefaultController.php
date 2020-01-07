<?php

namespace App\Controller;

use App\Entity\Login;
use App\Entity\UserUCO;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="connexion")
     */
    public function index()
    {
        /*$session = new Session();
        $session->start();*/

        return $this->render('menu/connexion.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/valideConnexion", name="connexionValidation")
     * @param Request $request
     * @return JsonResponse
     */
    public function validationConnexion(Request $request)
    {
        $username = $request->get('username');
        $mdp = $request->get('mdp');

        $em = $this->getDoctrine()->getManager("SYSTEME_INFO");

        $verif = $em->getRepository(Login::class)->findOneBy(["loginUsername" => $username, "loginPassword" => $mdp]);

        if ($verif != []) {
            $reponse = "ok";
            $user = $em->getRepository(UserUCO::class)->find($verif->getLoginIdUser());
            if(!isset($_SESSION))
                session_start();
            $_SESSION["user_data"] = $user;
        } else {
            $reponse = "Erreur dans le nom d'utilisateur ou le mot de passe !";
        }

        return new JsonResponse($reponse);
    }

    /**
     * @Route("/inscription", name="inscription")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function inscription(Request $request)
    {

        return $this->render('menu/inscription.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/inscription/validation", name="inscription_validation")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function inscriptionValidation(Request $request)
    {

        $nom = $request->get('nom');
        $prenom = $request->get('prenom');
        $mail = $request->get('mail');
        $username = $request->get('username');
        $mdp = $request->get('mdp');

        $em = $this->getDoctrine()->getManager("SYSTEME_INFO");
        $user = $em->getRepository(Login::class)->findOneBy(["loginUsername" => $username]);

        $response = "";

        if ($user != []) {
            $response .= "Nom d'utilisateur déja utilisé !";
        }

        $userMail = $em->getRepository(UserUCO::class)->findOneBy(["userEmail" => $mail]);

        if ($userMail != []) {
            if ($response != "") {
                $response .= "\n";
            }
            $response .= "Adresse mail déja utilisée !";
        }

        if ($response == "") {

            $date = new DateTime();
            $date = $date->format('Y-m-d');
            dump($date);
            $user = new UserUCO();
            $user->setUserDateArrivee($date)
                ->setUserRole("User")
                ->setUserEmail($mail)
                ->setUserPrenom($prenom)
                ->setUserNom($nom);

            $em->persist($user);
            $em->flush();

            $login = new Login();
            $login->setLoginIdUser($user->getIdUser())
                ->setLoginUsername($username)
                ->setLoginPassword($mdp);

            $em->persist($login);
            $em->flush();

            $response = "ok";
        }

        return new JsonResponse($response);
    }
}
