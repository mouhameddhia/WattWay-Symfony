<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\WarehouseRepository;

#[ORM\Entity(repositoryClass: WarehouseRepository::class)]
#[ORM\Table(name: 'warehouse')]
class Warehouse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $idWarehouse = null;

    public function getIdWarehouse(): ?int
    {
        return $this->idWarehouse;
    }

    public function setIdWarehouse(int $idWarehouse): self
    {
        $this->idWarehouse = $idWarehouse;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $city = null;

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $street = null;

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $postalCode = null;

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $capacityWarehouse = null;

    public function getCapacityWarehouse(): ?int
    {
        return $this->capacityWarehouse;
    }

    public function setCapacityWarehouse(int $capacityWarehouse): self
    {
        $this->capacityWarehouse = $capacityWarehouse;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Car::class, mappedBy: 'warehouse')]
    private Collection $cars;

    public function __construct()
    {
        $this->cars = new ArrayCollection();
    }

    /**
     * @return Collection<int, Car>
     */
    public function getCars(): Collection
    {
        if (!$this->cars instanceof Collection) {
            $this->cars = new ArrayCollection();
        }
        return $this->cars;
    }

    public function addCar(Car $car): self
    {
        if (!$this->getCars()->contains($car)) {
            $this->getCars()->add($car);
        }
        return $this;
    }

    public function removeCar(Car $car): self
    {
        $this->getCars()->removeElement($car);
        return $this;
    }

}
