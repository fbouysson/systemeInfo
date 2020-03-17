<?php

namespace App\Controller;

use App\Entity\Logs;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $this->getDoctrine()->getRepository(Logs::class)->createLog($this->getUser()->getUsername(),null,0);
            return $this->redirectToRoute('menu');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        dump($lastUsername);
        dump($error);
        dump($authenticationUtils);
        return $this->render('security/login.html.twig',
            [
                'last_username' => $lastUsername,
                'controller_name' => 'SecurityController',
                'error' => $error
            ]);
    }

    /**
     * @Route("/deconnexion", name="deconnexion")
     * @throws Exception
     */
    public function logdeco()
    {
        $this->getDoctrine()->getRepository(Logs::class)->createLog($this->getUser()->getUsername(),null,1);
        return $this->redirectToRoute("app_logout");
    }

    /**
     * @Route("/logout", name="app_logout")
     * @throws Exception
     */
    public function logout()
    {

        $this->getDoctrine()->getRepository(Logs::class)->createLog($this->getUser()->getUsername(),null,1);


        return $this->redirectToRoute("app_login");
    }
}
