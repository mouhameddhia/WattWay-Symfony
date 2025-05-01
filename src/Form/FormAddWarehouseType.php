<?php

namespace App\Form;

use App\Entity\Warehouse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Positive;

class FormAddWarehouseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('typeWarehouse', ChoiceType::class, [
            'choices' => [
                'Storage' => 'storage',
                'Repair' => 'repair',
            ],
            'expanded' => true,
            'multiple' => false,
            'label' => 'Status',
            'data' => $options['data']->getTypeWarehouse(),
        ])
            ->add('city', ChoiceType::class, [
                'choices' => [
                    'Tunis' => 'Tunis',
                    'Sfax' => 'Sfax',
                    'Sousse' => 'Sousse',
                    'Bizerte' => 'Bizerte',
                    'Gabès' => 'Gabes',
                    'Nabeul' => 'Nabeul',
                    'Kairouan' => 'Kairouan',
                    'Kasserine' => 'Kasserine',
                    'Monastir' => 'Monastir',
                    'Mahdia' => 'Mahdia',
                    'Zaghouan' => 'Zaghouan',
                    'Beja' => 'Beja',
                    'Jendouba' => 'Jendouba',
                    'Siliana' => 'Siliana',
                    'Al-Kaf' => 'Al-Kaf',
                    'Gafsa' => 'Gafsa',
                    'Tozeur' => 'Tozeur',
                    'Kebili' => 'Kebili',
                    'Medenine' => 'Medenine',
                    'Tataouine' => 'Tataouine',
                ],
                'placeholder' => 'Select a city',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('street', TextType::class, [
                'attr' => [
                    'placeholder' => 'Enter street address',
                ],
                'constraints' => [

                    new Regex([
                        'pattern' => '/^(?=.*[a-zA-Z])([a-zA-Z0-9 ]+)$/',
                        'message' => 'must contain only letters, numbers, and spaces.',
                    ]),
                ],
            ])
            ->add('postalCode', TextType::class, [
                'attr' => [
                    'placeholder' => 'Enter postal code',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\d{4}$/',
                        'message' => 'must be exactly 4 digits.',
                    ]),
                ],
            ])
            ->add('capacityWarehouse', NumberType::class, [
                'constraints' => [
                    new Positive(['message' => 'must be a positive number.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Warehouse::class,
        ]);
    }
}