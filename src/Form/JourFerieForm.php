<?php


namespace App\Form;

use App\Entity\JourFerie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class JourFerieForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'label' => 'Date du jour férié',
                'attr' => ['class' => 'datepicker']
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom (ex: Noël)',
                'constraints' => [new NotBlank()]
            ])
            ->add('zone', TextType::class, [
                'label' => 'Zone géographique',
                'required' => false,
                'attr' => ['placeholder' => 'Ex: Métropole']
            ]);
        // Removed 'annee' as it can be derived from date
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JourFerie::class,
        ]);
    }
}






