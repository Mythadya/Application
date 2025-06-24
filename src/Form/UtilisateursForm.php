<?php

namespace App\Form;

use App\Entity\Utilisateurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class UtilisateursForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('codepostal')
            ->add('ville')
            ->add('email')
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Gestionnaire' => 'ROLE_GESTIONNAIRE',
                    'Consultation' => 'ROLE_CONSULTATION',
                ],
                'label' => 'Rôle',
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
            ->add('dateInvitation', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'label' => 'Date d\'invitation',
                'attr' => [
                    'class' => 'form-control datetimepicker'
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control'
                ],
                'required' => $options['password_required']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class,
            'password_required' => true, // Default to true, can be overridden when creating the form
        ]);
        
        $resolver->setAllowedTypes('password_required', 'bool');
    }
}

















// namespace App\Form;

// use App\Entity\Utilisateurs;
// use Symfony\Component\Form\AbstractType;
// use Symfony\Component\Form\FormBuilderInterface;
// use Symfony\Component\OptionsResolver\OptionsResolver;

// class UtilisateursForm extends AbstractType
// {
//     public function buildForm(FormBuilderInterface $builder, array $options): void
//     {
//         $builder
//             ->add('nom')
//             ->add('prenom')
//             ->add('email')
//             ->add('role')
//             ->add('dateInvitation', null, [
//                 'widget' => 'single_text',
//             ])
//             ->add('password')
//         ;
//     }

//     public function configureOptions(OptionsResolver $resolver): void
//     {
//         $resolver->setDefaults([
//             'data_class' => Utilisateurs::class,
//         ]);
//     }
// }
