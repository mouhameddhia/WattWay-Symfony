<?php

namespace App\Form;

use App\Entity\Assignment;
use App\Entity\Car;
use App\Entity\Mechanic;
use App\Entity\AssignmentMechanics;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AssignmentType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('descriptionAssignment', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter assignment description',
                    'rows' => 4
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Description is required']),
                    new Length([
                        'min' => 10,
                        'max' => 1000,
                        'minMessage' => 'Description must be at least {{ limit }} characters long',
                        'maxMessage' => 'Description cannot be longer than {{ limit }} characters'
                    ])
                ]
            ])
            ->add('statusAssignment', ChoiceType::class, [
                'label' => 'Status',
                'choices' => [
                    'Pending' => 'pending',
                    'In Progress' => 'in_progress',
                    'Completed' => 'completed'
                ],
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Status is required']),
                    new Choice([
                        'choices' => ['pending', 'in_progress', 'completed'],
                        'message' => 'Please select a valid status'
                    ])
                ]
            ])
            ->add('dateAssignment', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'label' => 'Date',
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Date is required']),
                    new Type(['type' => '\DateTime', 'message' => 'Please enter a valid date'])
                ]
            ])
            ->add('car', EntityType::class, [
                'label' => 'Car',
                'class' => Car::class,
                'choice_label' => function(Car $car) {
                    return $car->getBrandCar() . ' ' . $car->getModelCar();
                },
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Please select a car'])
                ]
            ])
            ->add('assignmentMechanics', CollectionType::class, [
                'entry_type' => AssignmentMechanicsType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
                'prototype' => true,
                'prototype_name' => '__mechanics_prot__',
                'attr' => [
                    'class' => 'mechanics-collection'
                ]
            ]);
    }

    private function getMechanicsData(EntityManagerInterface $em): array
    {
        $mechanics = $em->getRepository(Mechanic::class)->findAll();
        $data = [];
        
        foreach ($mechanics as $mechanic) {
            $data[$mechanic->getIdMechanic()] = [
                'id' => $mechanic->getIdMechanic(),
                'name' => $mechanic->getNameMechanic(),
                'specialty' => $mechanic->getSpecialityMechanic()
            ];
        }
        
        return $data;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Assignment::class,
            'allow_extra_fields' => true, // Add this line
            'empty_data' => function () {
                return new Assignment();
            }
        ]);
    }
}