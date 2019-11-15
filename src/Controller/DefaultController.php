<?php

namespace App\Controller;

use App\Form\InscriptionType;
use App\src\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/connexion", name="homepage")
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
     * @Route("/connexionValidation", name="connexion")
     * @param Request $request
     * @return Response
     */
    public function connexion(Request $request)
    {
        $username = $request->get('username');
        $mdp = $request->get('mdp');

        if($username != "" && $mdp != "") {

            $em = $this->getDoctrine()->getManager("SYSTEME_INFO");

            $sql = "SELECT * FROM systeme_information.user where user_username= '$username' and user_password = '$mdp'";
            $statment = $em->getConnection()->prepare($sql);
            $statment->execute();
            $user = $statment->fetch();

            if ($user == []) {
                $response = false;
            } else {
                $response = true;

                dump($this->get("session"));
                //$session->set("user", $user);
            }

        }else{
            $response = false;
        }
        //$user = $em->getRepository(User::class)->findAll();

        //$em->getRepository(User::class)->findOneBy(["userUsername" => $username, "userPassword" => $mdp]);*/

        return new JsonResponse($response);
    }

    /**
     * @Route("/inscription", name="inscription")
     * @param Request $request
     * @return Response
     */
    public function inscription(Request $request)
    {
        $form = $this->createForm(InscriptionType::class);

        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager("SYSTEME_INFO");

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em->persist($data);
            $em->flush();
            //return $this->redirectToRoute('connexion');
            dump($data);
            dump($stop);
        }

        return $this->render('menu/inscription.html.twig', [
            'controller_name' => 'DefaultController',
            'form' => $form->createView(),
        ]);
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
