<?php

namespace App\Form;

use App\Entity\Mechanic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType; // Add this

class MechanicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nameMechanic')
            ->add('specialityMechanic')
            /*->add('imgMechanic', FileType::class, [  // Changed to FileType
                'label' => 'Profile Image',
                'mapped' => false,  // Prevents Symfony from trying to map to the entity property directly
                'required' => false, // Make optional if needed
                'attr' => [
                    'accept' => 'image/*'  // Restrict to image files
                ]
            ])
                */
            ->add('emailMechanic')
            ->add('carsRepaired')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mechanic::class,
        ]);
    }
}