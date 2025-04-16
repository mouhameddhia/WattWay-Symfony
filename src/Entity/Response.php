<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Submission;

#[ORM\Entity]
class Response
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $idResponse;

    #[ORM\Column(type: "text")]
    private string $message;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $dateResponse;

    #[ORM\Column(type: "string")]
    private string $typeResponse;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "responses")]
    #[ORM\JoinColumn(name: 'idUser', referencedColumnName: 'idUser', onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Submission::class, inversedBy: "responses")]
    #[ORM\JoinColumn(name: 'idSubmission', referencedColumnName: 'idSubmission', onDelete: 'CASCADE')]
    private ?Submission $submission = null;

    public function getIdResponse()
    {
        return $this->idResponse;
    }

    public function setIdResponse($value)
    {
        $this->idResponse = $value;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($value)
    {
        $this->message = $value;
    }

    public function getDateResponse()
    {
        return $this->dateResponse;
    }

    public function setDateResponse($value)
    {
        $this->dateResponse = $value;
    }

    public function getTypeResponse()
    {
        return $this->typeResponse;
    }

    public function setTypeResponse($value)
    {
        $this->typeResponse = $value;
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

    public function getSubmission(): ?Submission
    {
        return $this->submission;
    }

    public function setSubmission(?Submission $submission): self
    {
        $this->submission = $submission;
        return $this;
    }
}
