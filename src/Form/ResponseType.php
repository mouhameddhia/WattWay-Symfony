<?php

namespace App\Form;

use App\Entity\Response;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ResponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


        ->add('dateResponse', DateType::class, [
            'widget' => 'single_text',
            'attr' => [
                'class' => 'datepicker',
            ],
           
            'constraints' => [
                new NotBlank([
                    'message' => 'Date is required'
                ]),
            ],
            ])
            


            ->add('message', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Message is required'
                    ])
                ]
            ])
            
            ->add('typeResponse', ChoiceType::class, [
                'choices'  => [
                    'Acknowledgment' => 'ACKNOWLEDGMENT',
                    'Resolution' => 'RESOLUTION',
                    'Clarification Request' => 'CLARIFICATIONREQUEST'
                ],
                'placeholder' => 'Choose a response type',
                'required' => true
            ])
            ->add('idUser')
            ->add('idSubmission')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

           'data_cl
ass' => Response::class,
        ]);
    }
}

