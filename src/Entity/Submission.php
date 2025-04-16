<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\SubmissionRepository;

#[ORM\Entity(repositoryClass: SubmissionRepository::class)]
#[ORM\Table(name: 'submission')]
class Submission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $idSubmission = null;

    public function getIdSubmission(): ?int
    {
        return $this->idSubmission;
    }

    public function setIdSubmission(int $idSubmission): self
    {
        $this->idSubmission = $idSubmission;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: false)]
    private ?string $description = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $status = null;

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $urgencyLevel = null;

    public function getUrgencyLevel(): ?string
    {
        return $this->urgencyLevel;
    }

    public function setUrgencyLevel(string $urgencyLevel): self
    {
        $this->urgencyLevel = $urgencyLevel;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $dateSubmission = null;

    public function getDateSubmission(): ?\DateTimeInterface
    {
        return $this->dateSubmission;
    }

    public function setDateSubmission(\DateTimeInterface $dateSubmission): self
    {
        $this->dateSubmission = $dateSubmission;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
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

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $idUser = null;

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): self
    {
        $this->idUser = $idUser;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $last_modified = null;

    public function getLast_modified(): ?\DateTimeInterface
    {
        return $this->last_modified;
    }

    public function setLast_modified(\DateTimeInterface $last_modified): self
    {
        $this->last_modified = $last_modified;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $preferredContactMethod = null;

    public function getPreferredContactMethod(): ?string
    {
        return $this->preferredContactMethod;
    }

    public function setPreferredContactMethod(?string $preferredContactMethod): self
    {
        $this->preferredContactMethod = $preferredContactMethod;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $preferredAppointmentDate = null;

    public function getPreferredAppointmentDate(): ?\DateTimeInterface
    {
        return $this->preferredAppointmentDate;
    }

    public function setPreferredAppointmentDate(?\DateTimeInterface $preferredAppointmentDate): self
    {
        $this->preferredAppointmentDate = $preferredAppointmentDate;
        return $this;
    }

}
