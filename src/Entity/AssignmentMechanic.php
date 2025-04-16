<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\AssignmentMechanicRepository;

#[ORM\Entity(repositoryClass: AssignmentMechanicRepository::class)]
#[ORM\Table(name: 'assignment_mechanics')]
class AssignmentMechanic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $assignment_id = null;

    public function getAssignment_id(): ?int
    {
        return $this->assignment_id;
    }

    public function setAssignment_id(int $assignment_id): self
    {
        $this->assignment_id = $assignment_id;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $mechanic_id = null;

    public function getMechanic_id(): ?int
    {
        return $this->mechanic_id;
    }

    public function setMechanic_id(int $mechanic_id): self
    {
        $this->mechanic_id = $mechanic_id;
        return $this;
    }

}
