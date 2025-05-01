<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\OrderRepository;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: 'order')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $idOrder = null;

    public function getIdOrder(): ?int
    {
        return $this->idOrder;
    }

    public function setIdOrder(int $idOrder): self
    {
        $this->idOrder = $idOrder;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $supplierOrder = null;

    public function getSupplierOrder(): ?string
    {
        return $this->supplierOrder;
    }

    public function setSupplierOrder(string $supplierOrder): self
    {
        $this->supplierOrder = $supplierOrder;
        return $this;
    }

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $dateOrder = null;

    public function getDateOrder(): ?\DateTimeInterface
    {
        return $this->dateOrder;
    }

    public function setDateOrder(\DateTimeInterface $dateOrder): self
    {
        $this->dateOrder = $dateOrder;
        return $this;
    }

    #[ORM\Column(type: 'float')]
    private ?float $totalAmountOrder = null;

    public function getTotalAmountOrder(): ?float
    {
        return $this->totalAmountOrder;
    }

    public function setTotalAmountOrder(float $totalAmountOrder): self
    {
        $this->totalAmountOrder = $totalAmountOrder;
        return $this;
    }

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $statusOrder = null;

    public function getStatusOrder(): ?string
    {
        return $this->statusOrder;
    }

    public function setStatusOrder(string $statusOrder): self
    {
        $this->statusOrder = $statusOrder;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $idAdmin = null;

    public function getIdAdmin(): ?int
    {
        return $this->idAdmin;
    }

    public function setIdAdmin(?int $idAdmin): self
    {
        $this->idAdmin = $idAdmin;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $addressSupplierOrder = null;

    public function getAddressSupplierOrder(): ?string
    {
        return $this->addressSupplierOrder;
    }

    public function setAddressSupplierOrder(string $addressSupplierOrder): self
    {
        $this->addressSupplierOrder = $addressSupplierOrder;
        return $this;
    }

    #[ORM\Column(type: 'integer')]
    private ?int $idUser = null;

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): self
    {
        $this->idUser = $idUser;
        return $this;
    }

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
}
