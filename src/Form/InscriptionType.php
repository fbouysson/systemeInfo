<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id_user',HiddenType::class)
            ->add('user_nom',TextType::class, array("label"=>"Nom : ", "required" => true ,'attr' => array("class"=>"styl")))
            ->add('user_prenom',TextType::class, array("label"=>"Prenom : ", "required" => true ,'attr' => array("class"=>"styl")))
            ->add('user_email',TextType::class, array("label"=>"E-Mail : ", "required" => true ,'attr' => array("class"=>"styl")))
            ->add('user_date_arrivee',HiddenType::class)
            ->add('user_role',HiddenType::class)
            ->add('user_username',TextType::class, array("label" => "Nom d'utilisateur : ", "required" => true ,'attr' => array("class"=>"styl")))
            ->add('user_password',PasswordType::class, array("label" => "Mot de passe : ", "required" => true ,'attr' => array("class"=>"styl")));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
