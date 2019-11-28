<?php

namespace App\Form;

use App\Entity\Login;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionLoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('loginIdUser', HiddenType::class)
            ->add('loginUsername', TextType::class, array('label' => "Nom d'utilisateur : ", "attr" => array("class" => "form-control")))
            ->add('loginPassword', PasswordType::class, array("label" => "Mot de passe : ", "attr" => array("class" => "form-control")))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Login::class,
        ]);
    }
}
