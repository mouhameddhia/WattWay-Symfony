<?php

namespace App\Form;

use App\Entity\Submission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SubmissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'PENDING' => 'PENDING',
                    'APPROVED' => 'APPROVED',
                    'RESPONDED' => 'RESPONDED'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Status is required'
                    ])
                ]
            ])
            ->add('urgencyLevel', ChoiceType::class, [
                'choices' => [
                    'LOW' => 'LOW',
                    'MEDIUM' => 'MEDIUM',
                    'HIGH' => 'HIGH'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Urgency level is required'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Submission::class,
            'validation_groups' => ['edit'],
        ]);
    }
}
