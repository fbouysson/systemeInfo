<?php

namespace App\Form;

use App\Entity\UserUCO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userNom', TextType::class, array("label" => "Nom : ", "attr" => array("class" => "form-control")))
            ->add('userPrenom',TextType::class, array("label" => "Prénom : ", "attr" => array("class" => "form-control")))
            ->add('userEmail', EmailType::class, array("label" => "E-mail : ", "attr" => array("class" => "form-control")))
            ->add('userDateArrivee', HiddenType::class)
            ->add('userRole', HiddenType::class, array("required" => false))
            ->add('login', InscriptionLoginType::class, array("label"=>" "))
            ->add("submit", SubmitType::class, array("label" => "Rejoindre l'élite !", "attr" => array("class" => "btn btn-light btnInscription")))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserUCO::class,
        ]);
    }
}
