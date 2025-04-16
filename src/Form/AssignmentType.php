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
                'attr' => ['rows' => 5],
                'label' => 'Description',
                'required' => true
            ])
            ->add('statusAssignment', ChoiceType::class, [
                'choices' => [
                    'Pending' => 'Pending',
                    'In Progress' => 'In Progress',
                    'Completed' => 'Completed'
                ],
                'label' => 'Status',
                'required' => true
            ])
            ->add('dateAssignment', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'label' => 'Date',
                'required' => true
            ])
            ->add('car', EntityType::class, [
                'class' => Car::class,
                'choice_label' => function(Car $car) {
                    return $car->getBrandCar() . ' ' . $car->getModelCar();
                },
                'placeholder' => 'Select a car',
                'label' => 'Car',
                'required' => true
            ])
            ->add('mechanics', EntityType::class, [
                'class' => Mechanic::class,
                'choice_label' => 'nameMechanic',
                'multiple' => true,
                'expanded' => false,
                'placeholder' => 'Select mechanics',
                'label' => 'Mechanics',
                'mapped' => false,
                'required' => true
            ]);

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $assignment = $event->getData();
            $form = $event->getForm();

            if ($assignment) {
                $mechanics = [];
                foreach ($assignment->getAssignmentMechanics() as $assignmentMechanic) {
                    $mechanics[] = $assignmentMechanic->getIdMechanic();
                }
                $form->get('mechanics')->setData($mechanics);
            }
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $assignment = $event->getData();
            $form = $event->getForm();
            $mechanics = $form->get('mechanics')->getData();

            // Get existing mechanics for this assignment
            $existingMechanics = [];
            foreach ($assignment->getAssignmentMechanics() as $assignmentMechanic) {
                $existingMechanics[$assignmentMechanic->getIdMechanic()->getIdMechanic()] = $assignmentMechanic;
            }

            // Add new mechanics
            if ($mechanics) {
                foreach ($mechanics as $mechanic) {
                    $mechanicId = $mechanic->getIdMechanic();
                    if (!isset($existingMechanics[$mechanicId])) {
                        $assignmentMechanic = new AssignmentMechanics();
                        $assignmentMechanic->setIdAssignment($assignment);
                        $assignmentMechanic->setIdMechanic($mechanic);
                        $assignment->addAssignmentMechanic($assignmentMechanic);
                        $this->entityManager->persist($assignmentMechanic);
                    }
                }
            }

            // Remove mechanics that are no longer assigned
            foreach ($existingMechanics as $mechanicId => $assignmentMechanic) {
                $found = false;
                if ($mechanics) {
                    foreach ($mechanics as $mechanic) {
                        if ($mechanic->getIdMechanic() === $mechanicId) {
                            $found = true;
                            break;
                        }
                    }
                }
                if (!$found) {
                    $assignment->removeAssignmentMechanic($assignmentMechanic);
                    $this->entityManager->remove($assignmentMechanic);
                }
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Assignment::class,
            'empty_data' => function () {
                return new Assignment();
            }
        ]);
    }
}