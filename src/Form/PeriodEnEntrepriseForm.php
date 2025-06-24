<?php


namespace App\Form;

use App\Entity\Formation;
use App\Entity\PeriodEnEntreprise;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class PeriodEnEntrepriseForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début',
                'constraints' => [
                    new LessThanOrEqual([
                        'propertyPath' => 'parent.all[dateFin].data',
                        'message' => 'La date de début doit être avant la date de fin'
                    ])
                ]
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin'
            ])

              ->add('numbreHeures', IntegerType::class, [
                'label' => 'Numbre d\'heures',
                  'attr' => ['min' => 1]
            ])
            ->add('formation', EntityType::class, [
                'class' => Formation::class,
                'choice_label' => 'nom',
                'label' => 'Formation associée',
                'placeholder' => 'Sélectionnez une formation',
                'attr' => ['class' => 'select2']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PeriodEnEntreprise::class,
        ]);
    }
}



