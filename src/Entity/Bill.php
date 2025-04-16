<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
class Bill
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $idBill;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $dateBill;

    #[ORM\Column(type: "float")]
    private float $totalAmountBill;

    #[ORM\Column(type: "integer")]
    private int $idCar;

    #[ORM\Column(type: "integer")]
    private int $statusBill;

    #[ORM\Column(type: "integer")]
    private int $idUser;

    public function getIdBill()
    {
        return $this->idBill;
    }

    public function setIdBill($value)
    {
        $this->idBill = $value;
    }

    public function getDateBill()
    {
        return $this->dateBill;
    }

    public function setDateBill($value)
    {
        $this->dateBill = $value;
    }

    public function getTotalAmountBill()
    {
        return $this->totalAmountBill;
    }

    public function setTotalAmountBill($value)
    {
        $this->totalAmountBill = $value;
    }

    public function getIdCar()
    {
        return $this->idCar;
    }

    public function setIdCar($value)
    {
        $this->idCar = $value;
    }

    public function getStatusBill()
    {
        return $this->statusBill;
    }

    public function setStatusBill($value)
    {
        $this->statusBill = $value;
    }

    public function getIdUser()
    {
        return $this->idUser;
    }

    public function setIdUser($value)
    {
        $this->idUser = $value;
    }
}
