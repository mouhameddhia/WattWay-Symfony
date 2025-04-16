<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Warehouse;
use App\Repository\WarehouseRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class FormAddCarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
    ->add('modelCar', null, [
        'label' => 'Model',
        'constraints' => [
            new Regex([
                'pattern' => '/^(?!\s*$)[a-zA-Z0-9\s]+$/',
                'message' => 'Please enter a valid alphanumeric model.',
            ])
        ],
    ])
    ->add('brandCar', null, [
        'label' => 'Brand',
        'constraints' => [
            new Regex([
                'pattern' => '/^[a-zA-Z]+$/',
                'message' => 'Please enter a valid brand (letters only).',
            ])
        ],
    ])
    ->add('yearCar', null, [
        'label' => 'Year of Manufacture',
        'constraints' => [
            new Range([
                'min' => 1990,
                'max' => 2025,
                'notInRangeMessage' => 'The year must be between {{ min }} and {{ max }}.',
            ])
        ],
    ])
    ->add('priceCar', null, [
        'label' => 'Price',
        'constraints' => [
            new GreaterThanOrEqual([
                'value' => 0,
                'message' => 'The price must be a non-negative number.',
            ])
        ],
    ])
    ->add('kilometrageCar', null, [
        'label' => 'Kilometrage',
        'constraints' => [
            new GreaterThanOrEqual([
                'value' => 0,
                'message' => 'The kilometrage must be a non-negative number.',
            ])
        ],
    ])
    ->add('statusCar', ChoiceType::class, [
        'label' => 'Status',
        'choices' => [
            'Available' => 'available',
            'Sold' => 'sold',
            'Rented' => 'rented',
            'Under Repair' => 'under repair',
        ],
    ])
    ->add('imgCar', FileType::class, [
        'label' => 'Upload Image',
        'mapped' => false,
        'required' => false,
        'constraints' => [
            new File([
                'maxSize' => '2M',
                'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                'mimeTypesMessage' => 'Please upload a valid image (JPG, PNG, WEBP)',
            ])
        ],
    ])
    ->add('warehouse', EntityType::class, [
        'attr' => [
            'class' => 'form-control',
        ],
        'class' => Warehouse::class,
        'choice_label' => function (Warehouse $warehouse) {
            return $warehouse->getStreet() . ', ' . $warehouse->getCity() . ' (' . $warehouse->getPostalCode() . ')';
        },
        'query_builder' => function (WarehouseRepository $warehouseRepository) {
            return $warehouseRepository->createQueryBuilder('w');
        },
    ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
