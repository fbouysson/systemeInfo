<?php

namespace App\Form;

use App\Entity\UserUCO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class,
                [
                    'label' => "Nom d'utilisateur",
                    'disabled' => true,
                    'attr' => [
                        'class' => "form-control"
                    ]
                ])
            ->add('userEmail', EmailType::class,
                [
                    "label" => 'Adresse e-mail',
                    'disabled' => true,
                    'attr' => [
                        'class' => "form-control"
                    ]
                ])
            ->add('userDateArrivee', DateType::class,
                [
                    'label' => 'Date d\'inscription',
                    'disabled' => true,
                    'widget' => 'single_text',
                    'attr' => [
                        'class' => "form-control"
                    ]
                ])
            ->add('password', PasswordType::class,
                [
                    'label' => 'Changer le mot de passe',
                    'attr' => [
                        'class' => "form-control"
                    ]
                ])
            ->add('comfirmePassword',PasswordType::class,
                [
                    'label' => "Comfirmer le mot de passe",
                    'mapped' => false,
                    'attr' => [
                        'class' => "form-control"
                    ]
                ])
            ->add('submit', SubmitType::class,
                [
                    'label' => 'Enregistrer',
                    'attr' => [
                        'class' => "btn btn-primary"
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserUCO::class,
        ]);
    }
}
