<?php

namespace App\DataFixtures;
use App\Entity\Car;
use App\Entity\Submission;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Create a sample car
        $car = new Car();
        $car->setModelCar('Camry');
        $car->setBrandCar('Toyota');
        $car->setYearCar(2020);
        $car->setPriceCar(25000.00);
        $car->setStatusCar('active');
        $car->setKilometrageCar(50000.0);
        $car->setVinCode('1HGCM82633A123456');
        $manager->persist($car);

        // Create sample submission
        $submission = new Submission();
        $submission->setDescription('Car is making strange noises and needs immediate attention');
        $submission->setStatus('pending');
        $submission->setUrgencyLevel('high');
        $submission->setDateSubmission(new \DateTime());
        $submission->setCar($car);
        $submission->setIdUser(2);
        $submission->setLastModified(new \DateTime());
        $submission->setPreferredContactMethod('sms');
        $submission->setPreferredAppointmentDate((new \DateTime())->modify('+1 day'));
        $manager->persist($submission);

        $manager->flush();
    }
}