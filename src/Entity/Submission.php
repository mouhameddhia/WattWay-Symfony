<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\SubmissionRepository;

#[ORM\Entity(repositoryClass: SubmissionRepository::class)]
#[ORM\Table(name: 'submission')]
class Submission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idSubmission', type: 'integer')]
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

    #[ORM\Column(name: 'description',type: 'text', nullable: false)]
    #[Assert\NotBlank(message: 'Please provide a description of your service needs', groups: ['create'])]
    #[Assert\Length(
        min: 10,
        max: 1000,
        minMessage: 'Description must be at least {{ limit }} characters long',
        maxMessage: 'Description cannot be longer than {{ limit }} characters',
        groups: ['create']
    )]
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

    #[ORM\Column(name: 'status',type: 'string', nullable: false)]
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

    #[ORM\Column(name: 'urgencyLevel',type: 'string', nullable: false)]
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

    #[ORM\Column(name: 'dateSubmission', type: 'date', nullable: false)]
    #[AssertNotNull(message: 'Submission date is required', groups: ['create'])]
    #[AssertLessThanOrEqual('today', message: 'Submission date cannot be in the future', groups: ['create'])]
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

    #[ORM\Column(name: 'idCar', type: 'integer', nullable: false)]
    #[AssertNotNull(message: 'Car ID is required', groups: ['create'])]
    #[AssertPositive(message: 'Car ID must be a positive number', groups: ['create'])]
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

    #[ORM\Column(name: 'idUser', type: 'integer', nullable: false)]
    #[AssertNotNull(message: 'User ID is required', groups: ['create'])]
    #[AssertPositive(message: 'User ID must be a positive number', groups: ['create'])]
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

    #[ORM\Column(name: 'last_modified', type: 'datetime', nullable: false)]
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

    #[ORM\Column(name: 'preferredContactMethod', type: 'string', nullable: true)]
    #[Assert\Choice(choices: ['sms', 'phone', 'email'], message: 'Invalid contact method', groups: ['create'])]
    #[Assert\NotBlank(message: 'Type should not be blank.')]
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

    #[ORM\Column(name: 'preferredAppointmentDate', type: 'datetime', nullable: false)]
    #[Assert\GreaterThan('today', message: 'Appointment date must be in the future', groups: ['create'])]
    private ?\DateTimeInterface $preferredAppointmentDate = null;

    public function getPreferredAppointmentDate(): ?\DateTimeInterface
    {
        return $this->preferredAppointmentDate;
    }

    public function setPreferredAppointmentDate(\DateTimeInterface $preferredAppointmentDate): self
    {
        $this->preferredAppointmentDate = $preferredAppointmentDate;
        return $this;
    }

    public function getLastModified(): ?\DateTimeInterface
    {
        return $this->last_modified;
    }

    public function setLastModified(\DateTimeInterface $last_modified): static
    {
        $this->last_modified = $last_modified;
        return $this;
    }
}
