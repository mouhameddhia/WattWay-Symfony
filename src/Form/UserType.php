<?php
// src/Form/RegistrationFormType.php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstNameUser', TextType::class, [
                'label' => 'First Name',
                'attr' => ['placeholder' => 'First Name'],
               
            ])
            ->add('lastNameUser', TextType::class, [
                'label' => 'Last Name',
                'attr' => ['placeholder' => 'Last Name'],
               
            ])
            ->add('emailUser', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'Email'],
                
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'mapped' => false, // This ensures it is not persisted in the database
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Password cannot be blank.']),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Password must be at least {{ limit }} characters long.',
                    ]),
                ],
                'first_options' => [
                    'label' => 'Password',
                    'attr' => ['placeholder' => 'Password'],
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                    'attr' => ['placeholder' => 'Repeat Password'],
                ],
            ])
            
            ->add('phoneNumber', TelType::class, [
                'label' => 'Phone Number',
                'required' => false,
                'attr' => ['placeholder' => 'Phone Number'],
                
            ])
            ->add('address', TextareaType::class, [
                'label' => 'Address',
                'required' => false,
                'attr' => ['placeholder' => 'Address', 'rows' => 3],
            ])
            ->add('paymentDetails', ChoiceType::class, [
                'label' => 'Payment Method',
                'choices' => [
                    'PayPal' => 'PAYPAL',
                    'Credit Card' => 'CREDIT_CARD', 
                    'Bank Transfer' => 'BANK_TRANSFER',
                ],
                'placeholder' => 'Select a payment method',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'constraints' => new Assert\Valid(), 
        ]);
    }
}
