<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\AssignmentMechanics;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity]
class Mechanic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idMechanic", type: "integer")]
    private ?int $idMechanic=null;

    #[ORM\Column(name: "nameMechanic", type: "string", length: 255)]
    #[Assert\NotBlank(message: "Mechanic name is required")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "Mechanic name must be at least {{ limit }} characters long",
        maxMessage: "Mechanic name cannot be longer than {{ limit }} characters"
    )]
    private string $nameMechanic;

    #[ORM\Column(name: "specialityMechanic", type: "string", length: 255)]
    #[Assert\NotBlank(message: "Specialty is required")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "Specialty must be at least {{ limit }} characters long",
        maxMessage: "Specialty cannot be longer than {{ limit }} characters"
    )]
    #[Assert\Choice(
        choices: ['mechanic', 'software', 'electrician'],
        message: 'Specialty must be one of: mechanic, software, or electrician'
    )]
    private string $specialityMechanic;

    #[ORM\Column(name: "imgMechanic", type: "string", length: 255, nullable: true)]
    private ?string $imgMechanic = null;

    #[ORM\Column(name: "emailMechanic", type: "string", length: 255)]
    #[Assert\NotBlank(message: "Email is required")]
    #[Assert\Email(message: "The email '{{ value }}' is not a valid email")]
    private string $emailMechanic;

    #[ORM\Column(name: "carsRepaired", type: "integer")]
    #[Assert\Type(type: "integer", message: "Cars repaired must be a number")]
    #[Assert\PositiveOrZero(message: "Cars repaired cannot be negative")]
    private int $carsRepaired = 0;

    #[ORM\OneToMany(mappedBy: "idMechanic", targetEntity: Assignment::class)]
    private Collection $assignments;

    #[ORM\OneToMany(mappedBy: "idMechanic", targetEntity: AssignmentMechanics::class)]
    private Collection $assignmentMechanics;

    public function __construct()
    {
        $this->assignments = new ArrayCollection();
        $this->assignmentMechanics = new ArrayCollection();
    }

    public function getIdMechanic()
    {
        return $this->idMechanic;
    }

/*    public function setIdMechanic($value)
    {
        $this->idMechanic = $value;
    }
*/
    public function getNameMechanic()
    {
        return $this->nameMechanic;
    }

    public function setNameMechanic($value)
    {
        $this->nameMechanic = $value;
    }

    public function getSpecialityMechanic()
    {
        return $this->specialityMechanic;
    }

    public function setSpecialityMechanic($value)
    {
        $this->specialityMechanic = $value;
    }

    public function getImgMechanic()
    {
        return $this->imgMechanic;
    }

    public function setImgMechanic($value)
    {
        $this->imgMechanic = $value;
    }

    public function getEmailMechanic()
    {
        return $this->emailMechanic;
    }

    public function setEmailMechanic($value)
    {
        $this->emailMechanic = $value;
    }

    public function getCarsRepaired()
    {
        return $this->carsRepaired;
    }

    public function setCarsRepaired($value)
    {
        $this->carsRepaired = $value;
    }

    public function getAssignments(): Collection
    {
        return $this->assignments;
    }

    public function addAssignment($assignment): self
    {
        if (!$this->assignments->contains($assignment)) {
            $this->assignments[] = $assignment;
            $assignment->setIdMechanic($this);
        }

        return $this;
    }

    public function removeAssignment($assignment): self
    {
        if ($this->assignments->removeElement($assignment)) {
            if ($assignment->getIdMechanic() === $this) {
                $assignment->setIdMechanic(null);
            }
        }

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
            $assignmentMechanic->setIdMechanic($this);
        }
        return $this;
    }

    public function removeAssignmentMechanic(AssignmentMechanics $assignmentMechanic): self
    {
        $this->assignmentMechanics->removeElement($assignmentMechanic);
        return $this;
    }

    
    public function hasAssignments(EntityManagerInterface $em): bool
    {
        $assignmentRepo = $em->getRepository(AssignmentMechanics::class);
        return count($assignmentRepo->findBy(['idMechanic' => $this->idMechanic])) > 0;
    }
    // Add this method to get Assignment objects directly
    // Add this method to get Assignment objects directly
    public function getAssignmentsThroughJoin(): array
    {
        return $this->getAssignmentMechanics()->map(
            fn(AssignmentMechanics $am) => $am->getIdAssignment()
        )->toArray();
    }
    
}
