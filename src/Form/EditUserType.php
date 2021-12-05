<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => 'Votre email',
            'disabled' => true
        ])
          
        ->add('password', RepeatedType::class, [
            'constraints' => new Length([
                'min' => 2,
                'max' => 30
            ]),
            'type' => PasswordType::class,
            'invalid_message' => 'les mots de passe ne correspondent pas.',
            'required' => false,
            'first_options' => [
                'label' => 'Entrez votre nouveau mot de passe',
                'attr' => ['placeholder' => 'Entrez votre nouveau mot de passe']
            ],
            'second_options' => [
                'label' => 'Confirmez votre nouveau mot de passe',
                'attr' => ['placeholder' => 'Confirmez votre nouveau mot de passe']
            ],
        ])
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'disabled' => false
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'disabled' => false
            ])
            ->add('picture', FileType::class, [
                'required' => false,
                'label' => 'Photo',
                'data_class' => null,
                'attr' => [
                    'required' => false,
                    'data-allowed-file-extensions' => 'jpg jpeg png gif',
                    'class' => 'dropify',
                ]
            ])
            ->add('phone',TelType::class, [])
            ->add('buisness',TextType::class, [
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
