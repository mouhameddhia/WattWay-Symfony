<?php

namespace App\Form;
use App\Entity\Submission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class FrontSubmissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Please provide a description'])
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter service description'
                ]
            ])
            ->add('status', null, [
                'data' => 'pending',
                'attr' => ['style' => 'display: none;']
            ])
            ->add('urgencyLevel', null, [
                'data' => 'low',
                'attr' => ['style' => 'display: none;']
            ])
            ->add('dateSubmission', null, [
                'data' => new \DateTime(),
                'attr' => ['style' => 'display: none;']
            ])
            ->add('vinCode', null, [
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'VIN code is required'])
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter VIN code'
                ]
            ])
            ->add('idUser', null, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'User ID is required'])
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter User ID'
                ]
            ])
            ->add('preferredContactMethod', ChoiceType::class, [
                'choices' => [
                    'SMS' => 'sms',
                    'Phone' => 'phone',
                    'Email' => 'email'
                ],
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Please select a contact method'])
                ],
                'placeholder' => 'Choose contact method',
                'attr' => ['class' => 'form-select']
            ])
            ->add('preferredAppointmentDate', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(['message' => 'Please select an appointment date'])
                ],
                'attr' => ['class' => 'form-control'],
                'input' => 'datetime', // Ensure the input is converted to a DateTime object
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Submission::class,
            'validation_groups' => ['create'],
            'car_repository' => null,
            
        ]);
    }
}
