<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ItemRepository;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[ORM\Table(name: '`item`')]  // Backticks for table name if needed
class Item
{
    #[ORM\Id]
    #[ORM\Column(name: 'idItem', type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $idItem = null;

    #[ORM\Column(name: 'nameItem', type: 'string', length: 255, nullable: false)]
    private ?string $nameItem = null;

    #[ORM\Column(name: 'quantityItem', type: 'integer', nullable: false)]
    private ?int $quantityItem = null;

    #[ORM\Column(name: 'pricePerUnitItem', type: 'float', nullable: false)]
    private ?float $pricePerUnitItem = null;

    #[ORM\Column(name: 'categoryItem', type: 'string', length: 255, nullable: false)]
    private ?string $categoryItem = null;

    #[ORM\Column(name: 'orderId', type: 'integer', nullable: true)]
    private ?int $orderId = null;

    // Getters and Setters

    public function getIdItem(): ?int
    {
        return $this->idItem;
    }

    public function setIdItem(int $idItem): self
    {
        $this->idItem = $idItem;
        return $this;
    }

    public function getNameItem(): ?string
    {
        return $this->nameItem;
    }

    public function setNameItem(string $nameItem): self
    {
        $this->nameItem = $nameItem;
        return $this;
    }

    public function getQuantityItem(): ?int
    {
        return $this->quantityItem;
    }

    public function setQuantityItem(int $quantityItem): self
    {
        $this->quantityItem = $quantityItem;
        return $this;
    }

    public function getPricePerUnitItem(): ?float
    {
        return $this->pricePerUnitItem;
    }

    public function setPricePerUnitItem(float $pricePerUnitItem): self
    {
        $this->pricePerUnitItem = $pricePerUnitItem;
        return $this;
    }
    public function setCategoryItem(string $categoryItem):self
    {
        $this->categoryItem = $categoryItem;
        return $this;
    }
    public function getCategoryItem():string{
        return $this->categoryItem;
    }

    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    public function setOrderId(?int $orderId): self
    {
        $this->orderId = $orderId;
        return $this;
    }
}
