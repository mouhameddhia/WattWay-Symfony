<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Car;
use App\Entity\Mechanic;
use App\Entity\AssignmentMechanics;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Assignment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idAssignment", type: "integer")]
    private ?int $idAssignment = null;

    #[ORM\Column(name: "descriptionAssignment", type: "text")]
    #[Assert\NotBlank(message: "Description is required")]
    #[Assert\Length(
        min: 10,
        max: 1000,
        minMessage: "Description must be at least {{ limit }} characters long",
        maxMessage: "Description cannot be longer than {{ limit }} characters"
    )]
    private string $descriptionAssignment;

    #[ORM\Column(name: "statusAssignment", type: "string", length: 20)]
    #[Assert\NotBlank(message: "Status is required")]
    #[Assert\Choice(
        choices: ["Pending", "In Progress", "Completed"],
        message: "Status must be either pending, in-progress, or completed"
    )]
    private string $statusAssignment;

    #[ORM\ManyToOne(targetEntity:Car::class, inversedBy:"assignments")]
    #[ORM\JoinColumn(name: "idCar", referencedColumnName: "idCar")]
    #[Assert\NotBlank(message: "Car is required")]
    private ?Car $car = null;

    #[ORM\Column(name: "dateAssignment", type: "datetime")]
    #[Assert\NotBlank(message: "Date is required")]
    #[Assert\Type("\DateTimeInterface")]
    private \DateTimeInterface $dateAssignment;

    #[ORM\OneToMany(mappedBy: "idAssignment", targetEntity: AssignmentMechanics::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $assignmentMechanics;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $calendarEventId;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $calendarEventUrl;


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

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): self
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
    public function getMechanicNames(): array
    {
        return $this->getAssignmentMechanics()->map(
            fn(AssignmentMechanics $am) => $am->getIdMechanic()->getNameMechanic()
        )->toArray();
    }

    public function hasMechanic(Mechanic $mechanic): bool
    {
        foreach ($this->getAssignmentMechanics() as $am) {
            if ($am->getIdMechanic() === $mechanic) {
                return true;
            }
        }
        return false;
    }
    // Add this method to get Mechanic objects directly
    public function getMechanics(): array
    {
        return $this->getAssignmentMechanics()->map(
            fn(AssignmentMechanics $am) => $am->getIdMechanic()
        )->toArray();
    }
    public function getCalendarEventId(): ?string
    {
        return $this->calendarEventId;
    }

    public function setCalendarEventId(string $calendarEventId): self
    {
        $this->calendarEventId = $calendarEventId;
        return $this;
    }

    public function setCalendarEventUrl(string $url)
    {
        $this->calendarEventUrl = $url;
    }
}