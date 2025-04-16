<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\AssignmentRepository;

#[ORM\Entity(repositoryClass: AssignmentRepository::class)]
#[ORM\Table(name: 'assignment')]
class Assignment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $idAssignment = null;

    public function getIdAssignment(): ?int
    {
        return $this->idAssignment;
    }

    public function setIdAssignment(int $idAssignment): self
    {
        $this->idAssignment = $idAssignment;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $descriptionAssignment = null;

    public function getDescriptionAssignment(): ?string
    {
        return $this->descriptionAssignment;
    }

    public function setDescriptionAssignment(string $descriptionAssignment): self
    {
        $this->descriptionAssignment = $descriptionAssignment;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $statusAssignment = null;

    public function getStatusAssignment(): ?string
    {
        return $this->statusAssignment;
    }

    public function setStatusAssignment(string $statusAssignment): self
    {
        $this->statusAssignment = $statusAssignment;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $idMechanic = null;

    public function getIdMechanic(): ?int
    {
        return $this->idMechanic;
    }

    public function setIdMechanic(?int $idMechanic): self
    {
        $this->idMechanic = $idMechanic;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $idCar = null;

    public function getIdCar(): ?int
    {
        return $this->idCar;
    }

    public function setIdCar(int $idCar): self
    {
        $this->idCar = $idCar;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dateAssignment = null;

    public function getDateAssignment(): ?\DateTimeInterface
    {
        return $this->dateAssignment;
    }

    public function setDateAssignment(?\DateTimeInterface $dateAssignment): self
    {
        $this->dateAssignment = $dateAssignment;
        return $this;
    }

}
