<?php

namespace App\Form;

use App\Entity\UserUCO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("userNom", HiddenType::class, ["mapped" => false])
            ->add("userPrenom",HiddenType::class, ["mapped" => false])
            ->add("userDateArrivee", HiddenType::class, ["mapped" => false])
            ->add("userEmail", EmailType::class, ["label" => "Adresse mail :"])
            ->add("roles", HiddenType::class, ["mapped" => false])
            ->add('username', TextType::class, ["label" => "Nom d'utilisateur : "])
            ->add('agreeTerms', CheckboxType::class, [
                "label" => "Conditions d'utilisations",
                'mapped' => false,
                'attr' => ["Class" => "checkboxInput"],
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions d\'utilisation',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                "label" => "Mot de passe",
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit faire au moins 6 caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
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
