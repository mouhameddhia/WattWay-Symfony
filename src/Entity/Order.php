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

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $dateOrder = null;

    public function getDateOrder(): ?string
    {
        return $this->dateOrder;
    }

    public function setDateOrder(string $dateOrder): self
    {
        $this->dateOrder = $dateOrder;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: false)]
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

    #[ORM\Column(type: 'string', nullable: false)]
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

}
