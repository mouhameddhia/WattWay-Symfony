<?php

namespace App\Form;

use App\Entity\Mechanic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class MechanicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nameMechanic', TextType::class, [
                'label' => 'Name',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter mechanic name'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Mechanic name is required']),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Mechanic name must be at least {{ limit }} characters long',
                        'maxMessage' => 'Mechanic name cannot be longer than {{ limit }} characters'
                    ])
                ]
            ])
            ->add('specialityMechanic', ChoiceType::class, [
                'label' => 'Specialty',
                'choices' => [
                    'Mechanic' => 'mechanic',
                    'Software Engineer' => 'software',
                    'Electrician' => 'electrician'
                ],
                'attr' => [
                    'class' => 'form-control'
                ],
                'placeholder' => 'Select a specialty', // Optional empty choice
                'constraints' => [
                    new NotBlank(['message' => 'Please select a specialty'])
                ]
            ])
            ->add('emailMechanic', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter mechanic email'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Email is required']),
                    new Email(['message' => 'The email "{{ value }}" is not a valid email'])
                ]
            ])
            ->add('carsRepaired', IntegerType::class, [
                'label' => 'Cars Repaired',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter number of cars repaired'
                ],
                'constraints' => [
                    new Type(['type' => 'integer', 'message' => 'Cars repaired must be a number']),
                    new PositiveOrZero(['message' => 'Cars repaired cannot be negative'])
                ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mechanic::class,
        ]);
    }
}