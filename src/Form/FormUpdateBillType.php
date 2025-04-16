<?php

namespace App\Form;

use App\Entity\Bill;
use App\Entity\Car;
use App\Entity\User;
use App\Repository\CarRepository;
use App\Repository\UserRepository;
use Composer\Semver\Constraint\Constraint;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class FormUpdateBillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateBill', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datepicker',
                ],
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => new \DateTime('1990-01-01'),
                        'message' => 'must be a valid date.',
                    ]),
                    new LessThanOrEqual([
                        'value' => new \DateTime('now'),
                        'message' => 'Cannot be in the future, DUH.',
                    ]),
                ],
            ])
            ->add('totalAmountBill', null, [
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'must be a non-negative number.',
                    ]),
                ],
            ])
            ->add('statusBill', ChoiceType::class, [
                'choices' => [
                    'Paid' => 1,
                    'Pending' => 0,
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Status',
                'data' => $options['data']->getStatusBill(),
            ])
            ->add('car', EntityType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'class' => Car::class,
                'choice_label' => function (Car $car) {
                    return $car->getBrandCar() . ' ' . $car->getModelCar() . ' (' . $car->getYearCar() . ') - ' . $car->getPriceCar() . 'DT';
                },
                'query_builder' => function (CarRepository $carRepository) {
                    // Filter cars to include only those with status "available"
                    return $carRepository->createQueryBuilder('c')
                        ->where('c.statusCar = :status')
                        ->setParameter('status', 'available');
                },
            ])
            ->add('user', EntityType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return $user->getFirstNameUser() . ' ' . $user->getLastNameUser();
                },
                'query_builder' => function (UserRepository $userRepository) {
                    return $userRepository->createQueryBuilder('w')
                    ->where('w.roleUser LIKE :role')
                        ->setParameter('role', 'CLIENT');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bill::class,
        ]);
    }
}
