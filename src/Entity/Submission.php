<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use App\Entity\Response;

#[ORM\Entity]
class Submission
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $idSubmission;

    #[ORM\Column(type: "text")]
    private string $description;

    #[ORM\Column(type: "string")]
    private string $status;

    #[ORM\Column(type: "string")]
    private string $urgencyLevel;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $dateSubmission;

    #[ORM\Column(type: "integer")]
    private int $idCar;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "submissions")]
    #[ORM\JoinColumn(name: 'idUser', referencedColumnName: 'idUser', onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $last_modified;

    #[ORM\Column(type: "string", length: 50)]
    private string $preferredContactMethod;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $preferredAppointmentDate;

    public function getIdSubmission()
    {
        return $this->idSubmission;
    }

    public function setIdSubmission($value)
    {
        $this->idSubmission = $value;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($value)
    {
        $this->status = $value;
    }

    public function getUrgencyLevel()
    {
        return $this->urgencyLevel;
    }

    public function setUrgencyLevel($value)
    {
        $this->urgencyLevel = $value;
    }

    public function getDateSubmission()
    {
        return $this->dateSubmission;
    }

    public function setDateSubmission($value)
    {
        $this->dateSubmission = $value;
    }

    public function getIdCar()
    {
        return $this->idCar;
    }

    public function setIdCar($value)
    {
        $this->idCar = $value;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getLast_modified()
    {
        return $this->last_modified;
    }

    public function setLast_modified($value)
    {
        $this->last_modified = $value;
    }

    public function getPreferredContactMethod()
    {
        return $this->preferredContactMethod;
    }

    public function setPreferredContactMethod($value)
    {
        $this->preferredContactMethod = $value;
    }

    public function getPreferredAppointmentDate()
    {
        return $this->preferredAppointmentDate;
    }

    public function setPreferredAppointmentDate($value)
    {
        $this->preferredAppointmentDate = $value;
    }

    #[ORM\OneToMany(mappedBy: "idSubmission", targetEntity: Response::class)]
    private Collection $responses;

        public function getResponses(): Collection
        {
            return $this->responses;
        }
    
        public function addResponse(Response $response): self
        {
            if (!$this->responses->contains($response)) {
                $this->responses[] = $response;
                $response->setIdSubmission($this);
            }
    
            return $this;
        }
    
        public function removeResponse(Response $response): self
        {
            if ($this->responses->removeElement($response)) {
                // set the owning side to null (unless already changed)
                if ($response->getIdSubmission() === $this) {
                    $response->setIdSubmission(null);
                }
            }
    
            return $this;
        }
}
