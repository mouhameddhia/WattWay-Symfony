<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use App\Entity\Item;

#[ORM\Entity]
class Order
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $idOrder;

    #[ORM\Column(type: "string", length: 255)]
    private string $supplierOrder;

    #[ORM\Column(type: "string", length: 55)]
    private string $dateOrder;

    #[ORM\Column(type: "float")]
    private float $totalAmountOrder;

    #[ORM\Column(type: "string", length: 255)]
    private string $statusOrder;

    #[ORM\Column(type: "integer")]
    private int $idAdmin;

    #[ORM\Column(type: "string", length: 255)]
    private string $addressSupplierOrder;

    public function getIdOrder()
    {
        return $this->idOrder;
    }

    public function setIdOrder($value)
    {
        $this->idOrder = $value;
    }

    public function getSupplierOrder()
    {
        return $this->supplierOrder;
    }

    public function setSupplierOrder($value)
    {
        $this->supplierOrder = $value;
    }

    public function getDateOrder()
    {
        return $this->dateOrder;
    }

    public function setDateOrder($value)
    {
        $this->dateOrder = $value;
    }

    public function getTotalAmountOrder()
    {
        return $this->totalAmountOrder;
    }

    public function setTotalAmountOrder($value)
    {
        $this->totalAmountOrder = $value;
    }

    public function getStatusOrder()
    {
        return $this->statusOrder;
    }

    public function setStatusOrder($value)
    {
        $this->statusOrder = $value;
    }

    public function getIdAdmin()
    {
        return $this->idAdmin;
    }

    public function setIdAdmin($value)
    {
        $this->idAdmin = $value;
    }

    public function getAddressSupplierOrder()
    {
        return $this->addressSupplierOrder;
    }

    public function setAddressSupplierOrder($value)
    {
        $this->addressSupplierOrder = $value;
    }

    #[ORM\OneToMany(mappedBy: "orderId", targetEntity: Item::class)]
    private Collection $items;

        public function getItems(): Collection
        {
            return $this->items;
        }
    
        public function addItem(Item $item): self
        {
            if (!$this->items->contains($item)) {
                $this->items[] = $item;
                $item->setOrderId($this);
            }
    
            return $this;
        }
    
        public function removeItem(Item $item): self
        {
            if ($this->items->removeElement($item)) {
                // set the owning side to null (unless already changed)
                if ($item->getOrderId() === $this) {
                    $item->setOrderId(null);
                }
            }
    
            return $this;
        }
}
