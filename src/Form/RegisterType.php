<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, ['attr' => ['placeholder' => 'Email']])
        ->add('phone', TelType::class, ['label' => 'Telephone', 'attr' => ['placeholder' => 'Numero de telephone']])
        ->add('lastname', TextType::class, ['label' => 'Nom', 'attr' => ['placeholder' => 'Nom']])
        ->add('buisness', TextType::class, ['label' => 'Entreprise', 'attr' => ['placeholder' => 'Entreprise']])
        ->add('firstname', TextType::class, ['label' => 'Prenom', 'attr' => ['placeholder' => 'Prenom']])
        ->add('password', RepeatedType::class, [
            'constraints' => new Length([
                'min' => 2,
                'max' => 30
            ]),
            'type' => passwordType::class,
            'invalid_message' => 'les mots de passe ne correspondent pas.',
            'required' => true,
            'first_options' => [
                'label' => 'Entrez votre mot de passe',
                'attr' => ['placeholder' => 'Mot de passe']
            ],
            'second_options' => [
                'label' => 'Confirmez votre mot de passe',
                'attr' => ['placeholder' => 'Mot de passe']
            ],
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
