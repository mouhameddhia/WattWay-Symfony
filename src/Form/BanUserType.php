<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BanUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('duration', ChoiceType::class, [
                'choices' => [
                    '1 hour' => '1 hour',
                    '1 day' => '1 day',
                    '1 week' => '1 week',
                    '1 month' => '1 month',
                    'Custom' => 'custom',
                ],
                'label' => 'Ban Duration',
            ])
            ->add('customDate', DateTimeType::class, [
                'required' => false,
                'label' => 'Custom Ban Until',
                'widget' => 'single_text',
                'html5' => true,
            ])
            ->add('reason', TextareaType::class, [
                'required' => true,
                'label' => 'Reason for Ban',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}