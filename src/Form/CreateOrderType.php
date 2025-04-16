<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('supplierOrder', TextType::class, [
                'label' => 'Supplier Name',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Supplier Name',
                    'id' => 'supplierOrder'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Please provide a supplier name']),
                ],
                'row_attr' => ['class' => 'col-md-6']
            ])
            ->add('addressSupplierOrder', TextType::class, [
                'label' => 'Supplier Address',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Supplier Address',
                    'id' => 'addressSupplierOrder'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Please provide a supplier address']),
                ],
                'row_attr' => ['class' => 'col-md-6']
            ])
            ->add('totalAmountOrder', HiddenType::class, [
                'attr' => ['id' => 'hiddenTotalAmount']
            ])
            ->add('idAdmin', HiddenType::class, [
                'data' => '1',
                'attr' => ['id' => 'admin']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class, // We'll handle data manually
            'attr' => [
                'class' => 'order-form needs-validation',
                'novalidate' => 'novalidate',
                'id' => 'orderForm'
            ]
        ]);
    }
}



?>