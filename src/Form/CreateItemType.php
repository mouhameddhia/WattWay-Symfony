<?php

// src/Form/CreateItemType.php
namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\File;

class CreateItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nameItem', TextType::class, [
                'label' => 'Item Name',
                'attr' => ['placeholder' => 'Enter item name']
            ])
            ->add('categoryItem', TextType::class, [
                'label' => 'Category',
                'attr' => ['placeholder' => 'Enter item category']
            ])
            ->add('pricePerUnitItem', NumberType::class, [
                'label' => 'Price per Unit',
                'attr' => ['placeholder' => 'Enter price per unit']
            ])
            ->add('image', FileType::class, [
                'label' => 'Item Image (PNG, JPG, JPEG)',
                'mapped' => false,  // Important: the image is not mapped to the entity
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Please upload a valid image (JPEG/PNG).',
                    ])
                ],
                'attr' => ['accept' => 'image/*']
            ]);
    }
}
