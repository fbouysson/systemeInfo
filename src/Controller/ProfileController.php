<?php

namespace App\Controller;

use App\Entity\AffectationSalon;
use App\Entity\Logs;
use App\Entity\Salons;
use App\Entity\UserUCO;
use App\Form\ProfileType;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function index(Request $request ,UserPasswordEncoderInterface $passwordEncoder)
    {
        $em = $this->getDoctrine()->getManager("SYSTEME_INFO");

        $user = $this->getUser();
        $username = $user->getUsername();

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $usr = $em->getRepository(UserUCO::class)->find($user->getId());

            $usr->setPassword(
                $passwordEncoder->encodePassword(
                    $usr,
                    $form->get('password')->getData()
                )
            );

            $em->persist($usr);
            $em->flush();

            return $this->redirectToRoute('profile');
        }

        $salonsId = $em->getRepository(AffectationSalon::class)->findBy(["idUser" => $user->getId()]);
        $listSalon = "";

        foreach ($salonsId as $elt){
            $listSalon .= $elt->getIdSalon().",";
        }

        $listSalon = substr($listSalon, 0, -1);

        $salons = $em->getRepository(Salons::class)->findBy(["id" => explode(",",$listSalon)]);

        $tabSalonId = array();
        foreach ($salons as $sal ){
            $tabSalonId[] = $sal->getIdSalon();
        }

        $log = $em->getRepository(Logs::class)->findOneBy(['salon' => null , "action" => $username, "statut" => 0], ["datetime" => "desc"]);
        $dateLog = $log->getDatetime();
        $now = new DateTime();

        $sendWelcome = false;
        if($dateLog->diff($now)->s <= 3){
            $sendWelcome = true;
        }

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'profileForm' => $form->createView(),
            'user' => $user,
            'id' => $user->getId(),
            'username' => $username,
            'salons' => $salons,
            'tabSalonId' => $tabSalonId,
            'sendWelcome' => $sendWelcome,
        ]);
    }
}
