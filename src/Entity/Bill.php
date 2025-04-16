<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\BillRepository;

#[ORM\Entity(repositoryClass: BillRepository::class)]
#[ORM\Table(name: 'bill')]
class Bill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $idBill = null;

    public function getIdBill(): ?int
    {
        return $this->idBill;
    }

    public function setIdBill(int $idBill): self
    {
        $this->idBill = $idBill;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $dateBill = null;

    public function getDateBill(): ?\DateTimeInterface
    {
        return $this->dateBill;
    }

    public function setDateBill(\DateTimeInterface $dateBill): self
    {
        $this->dateBill = $dateBill;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: false)]
    private ?float $totalAmountBill = null;

    public function getTotalAmountBill(): ?float
    {
        return $this->totalAmountBill;
    }

    public function setTotalAmountBill(float $totalAmountBill): self
    {
        $this->totalAmountBill = $totalAmountBill;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $idCar = null;

    public function getIdCar(): ?int
    {
        return $this->idCar;
    }

    public function setIdCar(?int $idCar): self
    {
        $this->idCar = $idCar;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $statusBill = null;

    public function getStatusBill(): ?int
    {
        return $this->statusBill;
    }

    public function setStatusBill(?int $statusBill): self
    {
        $this->statusBill = $statusBill;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $idUser = null;

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(?int $idUser): self
    {
        $this->idUser = $idUser;
        return $this;
    }

}
