<?php

namespace App\Controller;

use App\Entity\CompteStatut;
use App\Entity\UserUCO;
use App\Form\RegistrationFormType;
use App\Security\UCOAuthenticator;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @param UCOAuthenticator $authenticator
     * @return Response
     * @throws \Exception
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UCOAuthenticator $authenticator): Response
    {
        $user = new UserUCO();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $token = bin2hex(random_bytes(10));

            $statut = new CompteStatut();
            $statut->setUsername($user->getUsername())
                ->setCompteStatut(false)
                ->setToken($token);

            $entityManager->persist($statut);
            $entityManager->flush();

            $corpmail = "Bonjour, merci de valider votre adresse EMail : <a href='http://192.168.1.36:81/register/validation/".$user->getUsername()."/".$token."'>Valider</a>";

            $mailer = new Swift_Mailer((new Swift_SmtpTransport('192.168.1.10', 25)));

            $message = (new Swift_Message('Hello Email'))
                ->setFrom('uco@gmail.com')
                ->setTo($user->getUserEmail())
                ->setBody(
                    $this->render(
                        'mailTemplate.html.twig',
                        ['title' => "Validation du mail", 'body' => $corpmail]
                    )
                );

            $mailer->send($message);

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/register/validation/{username}/{token}", name="validation_email")
     * @param $username
     * @param $token
     * @return Response
     */
    public function validationMail($username, $token){
        $em = $this->getDoctrine()->getManager('SYSTEME_INFO');

        $statut = $em->getRepository(CompteStatut::class)->findOneBy(['username' => $username]);

        if($statut && $statut->getToken() == $token){
            $statut->setToken(null);
            $statut->setCompteStatut(true);

            $em->flush();

            $message = "Email Vaidé !";
        }else{
            $message = "Erreur, votre email n'a pas pu être validé";
        }

        return $this->render('validation/validation.html.twig', [
            'message' => $message,
        ]);
    }
}
