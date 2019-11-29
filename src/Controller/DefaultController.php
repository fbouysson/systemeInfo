<?php

namespace App\Controller;

use App\Entity\Login;
use App\Form\InscriptionType;
use App\Entity\UserUCO;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/connexion", name="connexion")
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
     * @Route("/inscription", name="inscription")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function inscription(Request $request)
    {
        $form = $this->createForm(RegistrationFormType::class);

        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager("SYSTEME_INFO");

        if ($form->isSubmitted() && $form->isValid()) {
            dump($form->getData());

            $data = $form->getData();
            /*$user = new UserUCO();
             $user->setUserNom($data["user_nom"])
                 ->setUserPrenom($data["user_prenom"])
                 ->setUserEmail($data["user_email"])
                 ->setUserUsername($data["user_username"])
                 ->setUserPassword($data["user_password"])
                 ->setUserDateArrivee(new DateTime(date('Y-m-d H:i:s', strtotime('now +1 hour'))))
                 ->setUserRole("USER");*/

            $date = date('Y-m-d H:i:s', strtotime('now +1 hour'));
            //$sql = "INSERT INTO systeme_information.user (user_nom, user_prenom, user_email, user_date_arrivee, user_role, user_username, user_password) values (" . $data['user_nom'] . "," . $data['user_prenom'] . "," . $data['user_email'] . "," . $date . ",'USER'," . $data['user_username'] . "," . $data['user_password'] . ")";

            dump($data);

            /*$em->persist($user);
            $em->flush();*/
            dump($stop);
            return $this->redirectToRoute('connexion');
        }

        return $this->render('menu/inscription.html.twig', [
            'controller_name' => 'DefaultController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/inscription/validation", name="inscription_validation")
     * @param Request $request
     * @return Response
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

        if($user != []){
            $response .= "Nom d'utilisateur déja utilisé !";
        }

        $user = $em->getRepository(UserUCO::class)->findOneBy(["userEmail" => $mail]);

        if($user != []){
            if($response != ""){
                $response .= "\n";
            }
            $response .= "Adresse mail déja utilisée !";
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/menu", name="menu")
     * @param Request $request
     * @return Response
     */
    public function menu(Request $request)
    {

        return $this->render('menu/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
