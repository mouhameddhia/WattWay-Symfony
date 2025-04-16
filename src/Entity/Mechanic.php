<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Assignment_mechanics;
use phpDocumentor\Reflection\Types\Null_;

#[ORM\Entity]
class Mechanic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idMechanic", type: "integer")]
    private ?int $idMechanic=null;

    #[ORM\Column(name: "nameMechanic", type: "string", length: 255)]
    private string $nameMechanic;

    #[ORM\Column(name: "specialityMechanic", type: "string", length: 255)]
    private string $specialityMechanic;

    #[ORM\Column(name: "imgMechanic", type: "string", length: 255, nullable : true )]
    private ?string $imgMechanic = null;

    #[ORM\Column(name: "emailMechanic", type: "string", length: 255)]
    private string $emailMechanic;

    #[ORM\Column(name: "carsRepaired", type: "integer")]
    private int $carsRepaired;

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
}
