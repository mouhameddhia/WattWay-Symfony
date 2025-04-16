<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Car;
use App\Entity\Mechanic;
use App\Entity\AssignmentMechanics;

#[ORM\Entity]
class Assignment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idAssignment", type: "integer")]
    private ?int $idAssignment = null;

    #[ORM\Column(name: "descriptionAssignment", type: "string", length: 500)]
    private string $descriptionAssignment;

    #[ORM\Column(name: "statusAssignment", type: "string", length: 30)]
    private string $statusAssignment;

    #[ORM\ManyToOne(targetEntity: Car::class, inversedBy: "assignments")]
    #[ORM\JoinColumn(name: "idCar", referencedColumnName: "idCar", onDelete: "CASCADE", nullable: false)]
    private Car $car;

    #[ORM\Column(name: "dateAssignment", type: "datetime")]
    private \DateTimeInterface $dateAssignment;

    #[ORM\OneToMany(mappedBy: "idAssignment", targetEntity: AssignmentMechanics::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $assignmentMechanics;

    public function __construct()
    {
        $this->assignmentMechanics = new ArrayCollection();
    }

    public function getIdAssignment(): ?int
    {
        return $this->idAssignment;
    }

    public function getDescriptionAssignment(): string
    {
        return $this->descriptionAssignment;
    }

    public function setDescriptionAssignment(string $descriptionAssignment): self
    {
        $this->descriptionAssignment = $descriptionAssignment;
        return $this;
    }

    public function getStatusAssignment(): string
    {
        return $this->statusAssignment;
    }

    public function setStatusAssignment(string $statusAssignment): self
    {
        $this->statusAssignment = $statusAssignment;
        return $this;
    }

    public function getCar(): Car
    {
        return $this->car;
    }

    public function setCar(Car $car): self
    {
        $this->car = $car;
        return $this;
    }

    public function getDateAssignment(): \DateTimeInterface
    {
        return $this->dateAssignment;
    }

    public function setDateAssignment(\DateTimeInterface $dateAssignment): self
    {
        $this->dateAssignment = $dateAssignment;
        return $this;
    }

    public function getAssignmentMechanics(): Collection
    {
        return $this->assignmentMechanics;
    }

    public function addAssignmentMechanic(AssignmentMechanics $assignmentMechanic): self
    {
        if (!$this->assignmentMechanics->contains($assignmentMechanic)) {
            $this->assignmentMechanics[] = $assignmentMechanic;
            $assignmentMechanic->setIdAssignment($this);
        }
        return $this;
    }

    public function removeAssignmentMechanic(AssignmentMechanics $assignmentMechanic): self
    {
        $this->assignmentMechanics->removeElement($assignmentMechanic);
        return $this;
    }
}