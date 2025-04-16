<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\ItemRepository;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[ORM\Table(name: 'item')]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $idItem = null;

    public function getIdItem(): ?int
    {
        return $this->idItem;
    }

    public function setIdItem(int $idItem): self
    {
        $this->idItem = $idItem;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $nameItem = null;

    public function getNameItem(): ?string
    {
        return $this->nameItem;
    }

    public function setNameItem(string $nameItem): self
    {
        $this->nameItem = $nameItem;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $quantityItem = null;

    public function getQuantityItem(): ?int
    {
        return $this->quantityItem;
    }

    public function setQuantityItem(int $quantityItem): self
    {
        $this->quantityItem = $quantityItem;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: false)]
    private ?float $pricePerUnitItem = null;

    public function getPricePerUnitItem(): ?float
    {
        return $this->pricePerUnitItem;
    }

    public function setPricePerUnitItem(float $pricePerUnitItem): self
    {
        $this->pricePerUnitItem = $pricePerUnitItem;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $categoryItem = null;

    public function getCategoryItem(): ?string
    {
        return $this->categoryItem;
    }

    public function setCategoryItem(string $categoryItem): self
    {
        $this->categoryItem = $categoryItem;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $orderId = null;

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
