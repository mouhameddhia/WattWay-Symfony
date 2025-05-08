<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Mechanic;

#[ORM\Entity]
//#[ORM\Table(name: "assignment_mechanics")]
class AssignmentMechanics
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Assignment::class, inversedBy: "assignmentMechanics")]
    #[ORM\JoinColumn(name: 'idAssignment', referencedColumnName: 'idAssignment', onDelete: 'CASCADE')]
    private Assignment $idAssignment;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Mechanic::class, inversedBy: "assignmentMechanics")]
    #[ORM\JoinColumn(name: 'idMechanic', referencedColumnName: 'idMechanic', onDelete: 'CASCADE')]
    private Mechanic $idMechanic;

    public function getIdAssignment(): Assignment
    {
        return $this->idAssignment;
    }

    public function setIdAssignment(Assignment $idAssignment): self
    {
        $this->idAssignment = $idAssignment;
        return $this;
    }

    public function getIdMechanic(): Mechanic
    {
        return $this->idMechanic;
    }

    public function setIdMechanic(Mechanic $idMechanic): self
    {
        $this->idMechanic = $idMechanic;
        return $this;
    }
}
