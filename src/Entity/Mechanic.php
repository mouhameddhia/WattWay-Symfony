<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\MechanicRepository;

#[ORM\Entity(repositoryClass: MechanicRepository::class)]
#[ORM\Table(name: 'mechanic')]
class Mechanic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $idMechanic = null;

    public function getIdMechanic(): ?int
    {
        return $this->idMechanic;
    }

    public function setIdMechanic(int $idMechanic): self
    {
        $this->idMechanic = $idMechanic;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $nameMechanic = null;

    public function getNameMechanic(): ?string
    {
        return $this->nameMechanic;
    }

    public function setNameMechanic(string $nameMechanic): self
    {
        $this->nameMechanic = $nameMechanic;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $specialityMechanic = null;

    public function getSpecialityMechanic(): ?string
    {
        return $this->specialityMechanic;
    }

    public function setSpecialityMechanic(string $specialityMechanic): self
    {
        $this->specialityMechanic = $specialityMechanic;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $imgMechanic = null;

    public function getImgMechanic(): ?string
    {
        return $this->imgMechanic;
    }

    public function setImgMechanic(?string $imgMechanic): self
    {
        $this->imgMechanic = $imgMechanic;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $emailMechanic = null;

    public function getEmailMechanic(): ?string
    {
        return $this->emailMechanic;
    }

    public function setEmailMechanic(string $emailMechanic): self
    {
        $this->emailMechanic = $emailMechanic;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $carsRepaired = null;

    public function getCarsRepaired(): ?int
    {
        return $this->carsRepaired;
    }

    public function setCarsRepaired(?int $carsRepaired): self
    {
        $this->carsRepaired = $carsRepaired;
        return $this;
    }

}
