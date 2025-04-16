<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
class Warehouse
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $idWarehouse;

    #[ORM\Column(type: "integer")]
    private int $capacityWarehouse;

    #[ORM\Column(type: "string", length: 100)]
    private string $city;

    #[ORM\Column(type: "string", length: 255)]
    private string $street;

    #[ORM\Column(type: "string", length: 20)]
    private string $postalCode;

    public function getIdWarehouse()
    {
        return $this->idWarehouse;
    }

    public function setIdWarehouse($value)
    {
        $this->idWarehouse = $value;
    }

    public function getCapacityWarehouse()
    {
        return $this->capacityWarehouse;
    }

    public function setCapacityWarehouse($value)
    {
        $this->capacityWarehouse = $value;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($value)
    {
        $this->city = $value;
    }

    public function getStreet()
    {
        return $this->street;
    }

    public function setStreet($value)
    {
        $this->street = $value;
    }

    public function getPostalCode()
    {
        return $this->postalCode;
    }

    public function setPostalCode($value)
    {
        $this->postalCode = $value;
    }
}
