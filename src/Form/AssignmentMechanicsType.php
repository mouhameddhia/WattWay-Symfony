<?php

namespace App\Form;

use App\Entity\AssignmentMechanics;
use App\Entity\Mechanic;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssignmentMechanicsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idMechanic', EntityType::class, [
                'class' => Mechanic::class,
                'choice_label' => 'nameMechanic',
                'label' => 'Mechanic',
                'attr' => [
                    'class' => 'form-control mechanic-select'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AssignmentMechanics::class,
        ]);
    }
}