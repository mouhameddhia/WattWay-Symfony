<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\CarRepository;

#[ORM\Entity(repositoryClass: CarRepository::class)]
#[ORM\Table(name: 'car')]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
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

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $modelCar = null;

    public function getModelCar(): ?string
    {
        return $this->modelCar;
    }

    public function setModelCar(string $modelCar): self
    {
        $this->modelCar = $modelCar;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $brandCar = null;

    public function getBrandCar(): ?string
    {
        return $this->brandCar;
    }

    public function setBrandCar(string $brandCar): self
    {
        $this->brandCar = $brandCar;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $yearCar = null;

    public function getYearCar(): ?int
    {
        return $this->yearCar;
    }

    public function setYearCar(int $yearCar): self
    {
        $this->yearCar = $yearCar;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: false)]
    private ?float $priceCar = null;

    public function getPriceCar(): ?float
    {
        return $this->priceCar;
    }

    public function setPriceCar(float $priceCar): self
    {
        $this->priceCar = $priceCar;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $statusCar = null;

    public function getStatusCar(): ?string
    {
        return $this->statusCar;
    }

    public function setStatusCar(string $statusCar): self
    {
        $this->statusCar = $statusCar;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: false)]
    private ?float $kilometrageCar = null;

    public function getKilometrageCar(): ?float
    {
        return $this->kilometrageCar;
    }

    public function setKilometrageCar(float $kilometrageCar): self
    {
        $this->kilometrageCar = $kilometrageCar;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Warehouse::class, inversedBy: 'cars')]
    #[ORM\JoinColumn(name: 'idWarehouse', referencedColumnName: 'idWarehouse')]
    private ?Warehouse $warehouse = null;

    public function getWarehouse(): ?Warehouse
    {
        return $this->warehouse;
    }

    public function setWarehouse(?Warehouse $warehouse): self
    {
        $this->warehouse = $warehouse;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $imgCar = null;

    public function getImgCar(): ?string
    {
        return $this->imgCar;
    }

    public function setImgCar(?string $imgCar): self
    {
        $this->imgCar = $imgCar;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $vinCode = null;

    public function getVinCode(): ?string
    {
        return $this->vinCode;
    }

    public function setVinCode(?string $vinCode): self
    {
        $this->vinCode = $vinCode;
        return $this;
    }

}
