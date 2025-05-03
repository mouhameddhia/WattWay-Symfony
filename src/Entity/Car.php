<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Assignment;
use App\Entity\User;

#[ORM\Entity]
class Car
{
    #[ORM\Id]
    #[ORM\Column(name: "idCar", type: "integer")]
    private int $idCar;

    #[ORM\Column(type: "integer")]
    private int $idWarehouse;

    #[ORM\Column(type: "string", length: 100)]
    private string $modelCar;

    #[ORM\Column(type: "string", length: 100)]
    private string $brandCar;

    #[ORM\Column(type: "integer")]
    private int $yearCar;

    #[ORM\Column(type: "float")]
    private float $priceCar;

    #[ORM\Column(type: "float")]
    private float $kilometrageCar;

    #[ORM\Column(type: "string", length: 20)]
    private string $statusCar;

    #[ORM\Column(type: "string", length: 255)]
    private string $imgCar;

    #[ORM\Column(type: "string", length: 17)]
    private string $vin_code;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "cars")]
    #[ORM\JoinColumn(name: "idUser", referencedColumnName: "idUser", nullable: true, onDelete: "SET NULL")]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: "car", targetEntity: Assignment::class)]
    private Collection $assignments;

    public function __construct()
    {
        // initialize collections
        $this->assignments = new ArrayCollection();
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    // ... existing getters and setters for other fields ...

    public function getIdCar()
    {
        return $this->idCar;
    }

    public function setIdCar($value)
    {
        $this->idCar = $value;
    }

    public function getIdWarehouse()
    {
        return $this->idWarehouse;
    }

    public function setIdWarehouse($value)
    {
        $this->idWarehouse = $value;
    }

    public function getModelCar()
    {
        return $this->modelCar;
    }

    public function setModelCar($value)
    {
        $this->modelCar = $value;
    }

    public function getBrandCar()
    {
        return $this->brandCar;
    }

    public function setBrandCar($value)
    {
        $this->brandCar = $value;
    }

    public function getYearCar()
    {
        return $this->yearCar;
    }

    public function setYearCar($value)
    {
        $this->yearCar = $value;
    }

    public function getPriceCar()
    {
        return $this->priceCar;
    }

    public function setPriceCar($value)
    {
        $this->priceCar = $value;
    }

    public function getKilometrageCar()
    {
        return $this->kilometrageCar;
    }

    public function setKilometrageCar($value)
    {
        $this->kilometrageCar = $value;
    }

    public function getStatusCar()
    {
        return $this->statusCar;
    }

    public function setStatusCar($value)
    {
        $this->statusCar = $value;
    }

    public function getImgCar()
    {
        return $this->imgCar;
    }

    public function setImgCar($value)
    {
        $this->imgCar = $value;
    }

    public function getVin_code()
    {
        return $this->vin_code;
    }

    public function setVin_code($value)
    {
        $this->vin_code = $value;
    }

    public function getAssignments(): Collection
    {
        return $this->assignments;
    }

    public function addAssignment(Assignment $assignment): self
    {
        if (!$this->assignments->contains($assignment)) {
            $this->assignments[] = $assignment;
            $assignment->setCar($this);
        }

        return $this;
    }

    public function removeAssignment(Assignment $assignment): self
    {
        if ($this->assignments->removeElement($assignment)) {
            // no additional action needed here
        }

        return $this;
    }
}
