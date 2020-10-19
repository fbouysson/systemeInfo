<?php

namespace App\Controller;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class Mail extends AbstractController
{
    /**
     * @Route("/mail", name="mail")
     * @return RedirectResponse
     */
    public function sendMailInscription(){

        $mailer = new Swift_Mailer((new Swift_SmtpTransport('192.168.1.36', 465)));

        $message = (new Swift_Message('Hello Email'))
            ->setFrom('florian.b.1998@hotmail.fr')
            ->setTo('florian.bouysson@outlook.fr')
            ->setBody(
                $this->render(
                    'mailTemplate.html.twig',
                    ['title' => "HelloMAil", 'body' => "coucou"]
                )
            );

        $mailer->send($message);

        return $this->redirectToRoute('profile');
    }
}