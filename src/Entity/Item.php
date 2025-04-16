<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Order;

#[ORM\Entity]
class Item
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $idItem;

    #[ORM\Column(type: "string", length: 255)]
    private string $nameItem;

    #[ORM\Column(type: "integer")]
    private int $quantityItem;

    #[ORM\Column(type: "float")]
    private float $pricePerUnitItem;

    #[ORM\Column(type: "string", length: 100)]
    private string $categoryItem;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: "items")]
    #[ORM\JoinColumn(name: 'orderId', referencedColumnName: 'idOrder', onDelete: 'CASCADE')]
    private ?Order $order = null;

    public function getIdItem()
    {
        return $this->idItem;
    }

    public function setIdItem($value)
    {
        $this->idItem = $value;
    }

    public function getNameItem()
    {
        return $this->nameItem;
    }

    public function setNameItem($value)
    {
        $this->nameItem = $value;
    }

    public function getQuantityItem()
    {
        return $this->quantityItem;
    }

    public function setQuantityItem($value)
    {
        $this->quantityItem = $value;
    }

    public function getPricePerUnitItem()
    {
        return $this->pricePerUnitItem;
    }

    public function setPricePerUnitItem($value)
    {
        $this->pricePerUnitItem = $value;
    }

    public function getCategoryItem()
    {
        return $this->categoryItem;
    }

    public function setCategoryItem($value)
    {
        $this->categoryItem = $value;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;
        return $this;
    }
}
