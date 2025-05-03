<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

use App\Repository\CarRepository;

#[ORM\Entity(repositoryClass: CarRepository::class)]
#[ORM\Table(name: 'car')]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer' , name: 'idCar')]
    #[Groups(['car:read'])]

    private ?int $idCar = null;

    public function getIdCar(): ?int
    {
        return $this->idCar;
    }

    public function setIdCar(int $idCar): self
    {
        $this->idCar = $idCar;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false , name: 'modelCar')]
    #[Groups(['bill:read', 'car:read'])]
    private ?string $modelCar = null;

    public function getModelCar(): ?string
    {
        return $this->modelCar;
    }

    public function setModelCar(string $modelCar): self
    {
        $this->modelCar = $modelCar;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false , name: 'brandCar')]
    #[Groups(['bill:read', 'car:read'])]
    private ?string $brandCar = null;

    public function getBrandCar(): ?string
    {
        return $this->brandCar;
    }

    public function setBrandCar(string $brandCar): self
    {
        $this->brandCar = $brandCar;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false , name: 'yearCar')]
    #[Groups(['car:read'])]
    private ?int $yearCar = null;

    public function getYearCar(): ?int
    {
        return $this->yearCar;
    }

    public function setYearCar(int $yearCar): self
    {
        $this->yearCar = $yearCar;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: false , name: 'priceCar')]
    #[Groups(['car:read'])]
    private ?float $priceCar = null;

    public function getPriceCar(): ?float
    {
        return $this->priceCar;
    }

    public function setPriceCar(float $priceCar): self
    {
        $this->priceCar = $priceCar;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false , name: 'statusCar')]
    #[Groups(['car:read'])]
    private ?string $statusCar = null;

    public function getStatusCar(): ?string
    {
        return $this->statusCar;
    }

    public function setStatusCar(string $statusCar): self
    {
        $this->statusCar = $statusCar;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: false , name: 'kilometrageCar')]
    #[Groups(['car:read'])]
    private ?float $kilometrageCar = null;

    public function getKilometrageCar(): ?float
    {
        return $this->kilometrageCar;
    }

    public function setKilometrageCar(float $kilometrageCar): self
    {
        $this->kilometrageCar = $kilometrageCar;
        return $this;
    }

    #[ORM\OneToMany(mappedBy: "car", targetEntity: Assignment::class)]
    private Collection $assignments;

    #[ORM\ManyToOne(targetEntity: Warehouse::class, inversedBy: 'cars')]
    #[ORM\JoinColumn(name: 'idWarehouse', referencedColumnName: 'idWarehouse')]
    #[Groups(['car:read'])]
    private ?Warehouse $warehouse = null;

    public function getWarehouse(): ?Warehouse
    {
        return $this->warehouse;
    }

    public function setWarehouse(?Warehouse $warehouse): self
    {
        $this->warehouse = $warehouse;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'cars')]
    #[ORM\JoinColumn(name: 'idUser', referencedColumnName: 'idUser')]
    #[Groups(['car:read'])]
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

    #[ORM\Column(type: 'string', nullable: true , name: 'imgCar')]
    #[Groups(['car:read'])]
    private ?string $imgCar = null;

    public function getImgCar(): ?string
    {
        return $this->imgCar;
    }

    public function setImgCar(?string $imgCar): self
    {
        $this->imgCar = $imgCar;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true , name: 'vinCode')]
    private ?string $vinCode = null;

    public function getVinCode(): ?string
    {
        return $this->vinCode;
    }

    public function setVinCode(?string $vinCode): self
    {
        $this->vinCode = $vinCode;
        return $this;
    }
    #[ORM\OneToMany(targetEntity: Bill::class, mappedBy: 'car')]
    private Collection $bills;
    public function __construct()
    {
        $this->bills = new ArrayCollection();
    }
    /**
     * @return Collection<int, Bill>
     */
    public function getBills(): Collection
    {
        if (!$this->bills instanceof Collection) {
            $this->bills = new ArrayCollection();
        }
        return $this->bills;
    }
    public function addBill(Bill $bill): self
    {
        if (!$this->bills->contains($bill)) {
            $this->bills[] = $bill;
            $bill->setCar($this);
        }
        return $this;
    }
    public function removeBill(Bill $bill): self
    {
        if ($this->bills->removeElement($bill)) {
            // set the owning side to null (unless already changed)
            if ($bill->getCar() === $this) {
                $bill->setCar(null);
            }
        }
        return $this;
    }
        public function getAssignments(): Collection
    {
        return $this->assignments;
    }

    public function addAssignment(Assignment $assignment): self
    {
        if (!$this->assignments->contains($assignment)) {
            $this->assignments[] = $assignment;
            $assignment->setCar($this);
        }

        return $this;
    }

    public function removeAssignment(Assignment $assignment): self
    {
        if ($this->assignments->removeElement($assignment)) {
            // no additional action needed here
        }

        return $this;
    }

}
