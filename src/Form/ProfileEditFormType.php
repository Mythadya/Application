<?php
// src/Form/ProfileEditFormType.php
namespace App\Form;

use App\Entity\Utilisateurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfileEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'given-name'
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'family-name'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'disabled' => true,
                'attr' => [
                    'class' => 'form-control bg-light',
                    'readonly' => 'readonly',
                    'style' => 'cursor: not-allowed;'
                ]
            ])
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Mot de passe actuel',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'autocomplete' => 'current-password',
                    'class' => 'form-control',
                    'placeholder' => 'Laissez vide si inchangé'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le mot de passe actuel est requis pour modifier',
                        'groups' => ['password_change']
                    ])
                ]
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'required' => false,
                'first_options' => [
                    'label' => 'Nouveau mot de passe',
                    'attr' => [
                        'class' => 'form-control',
                        'autocomplete' => 'new-password',
                        'placeholder' => 'Minimum 8 caractères'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmation',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Répétez le mot de passe'
                    ]
                ],
                'constraints' => [
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères',
                        'groups' => ['password_change']
                    ])
                ],
                'invalid_message' => 'Les mots de passe doivent correspondre'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class,
            'validation_groups' => function ($form) {
                $groups = ['Default'];
                if ($form->get('newPassword')->getData()) {
                    $groups[] = 'password_change';
                }
                return $groups;
            },
            'attr' => ['novalidate' => 'novalidate'] // Disable HTML5 validation
        ]);
    }
}