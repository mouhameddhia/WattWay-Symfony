<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\Column(name: 'idOrder', type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]  // This makes the id auto-incremented
    private ?int $idOrder = null;

    #[ORM\Column(name: 'supplierOrder', type: 'string', length: 255)]
    private ?string $supplierOrder = null;

    #[ORM\Column(name: 'dateOrder', type: 'datetime')]
    private ?\DateTimeInterface $dateOrder = null;

    #[ORM\Column(name: 'totalAmountOrder', type: 'float')]
    private ?float $totalAmountOrder = null;

    #[ORM\Column(name: 'statusOrder', type: 'string', length: 100)]
    private ?string $statusOrder = null;

    #[ORM\Column(name: 'addressSupplierOrder', type: 'string', length: 255)]
    private ?string $addressSupplierOrder = null;

    #[ORM\Column(name: 'idAdmin', type: 'integer')]
    private ?int $idAdmin = null;

    // Getters and setters

    public function getIdOrder(): ?int
    {
        return $this->idOrder;
    }

    public function setIdOrder(int $idOrder): static
    {
        $this->idOrder = $idOrder;

        return $this;
    }

    public function getSupplierOrder(): ?string
    {
        return $this->supplierOrder;
    }

    public function setSupplierOrder(string $supplierOrder): static
    {
        $this->supplierOrder = $supplierOrder;

        return $this;
    }

    public function getDateOrder(): ?\DateTimeInterface
    {
        return $this->dateOrder;
    }

    public function setDateOrder(\DateTimeInterface $dateOrder): static
    {
        $this->dateOrder = $dateOrder;

        return $this;
    }

    public function getTotalAmountOrder(): ?float
    {
        return $this->totalAmountOrder;
    }

    public function setTotalAmountOrder(float $totalAmountOrder): static
    {
        $this->totalAmountOrder = $totalAmountOrder;

        return $this;
    }

    public function getStatusOrder(): ?string
    {
        return $this->statusOrder;
    }

    public function setStatusOrder(string $statusOrder): static
    {
        $this->statusOrder = $statusOrder;

        return $this;
    }

    public function getAddressSupplierOrder(): ?string
    {
        return $this->addressSupplierOrder;
    }

    public function setAddressSupplierOrder(string $addressSupplierOrder): static
    {
        $this->addressSupplierOrder = $addressSupplierOrder;

        return $this;
    }

    public function getIdAdmin(): ?int
    {
        return $this->idAdmin;
    }

    public function setIdAdmin(int $idAdmin): static
    {
        $this->idAdmin = $idAdmin;

        return $this;
    }
}
