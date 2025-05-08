<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

use App\Repository\BillRepository;

#[ORM\Entity(repositoryClass: BillRepository::class)]
#[ORM\Table(name: 'bill')]
class Bill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', name: 'idBill')]
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
    #[ORM\Column(name:'dateBill',type: 'datetime', nullable: false)]
    #[Groups(['bill:read'])]
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
    public function __construct()
{
    $this->dateBill = new \DateTimeImmutable(); // ensures it's always set
}
    #[ORM\Column(type: 'float', nullable: false, name: 'totalAmountBill')]
    #[Groups(['bill:read'])]
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

    #[ORM\ManyToOne(targetEntity: Car::class, inversedBy: 'bills')]
    #[ORM\JoinColumn(name: 'idCar', referencedColumnName: 'idCar')]
    #[Groups(['bill:read'])]
    private ?Car $car = null;
    public function getCar(): ?Car
    {
        return $this->car;
    }
    public function setCar(?Car $car): self
    {
        $this->car = $car;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: true, name: 'statusBill')]
    #[Groups(['bill:read'])]
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

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'bills')]
    #[ORM\JoinColumn(name: 'idUser', referencedColumnName: 'idUser')]
    #[Groups(['bill:read'])]
    private ?User $user = null;
    public function getUser(): ?User
    {
        return $this->user;
    }
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

}
