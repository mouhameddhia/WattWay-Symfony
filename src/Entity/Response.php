<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\ResponseRepository;

#[ORM\Entity(repositoryClass: ResponseRepository::class)]
#[ORM\Table(name: 'response')]
class Response
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $idResponse = null;

    public function getIdResponse(): ?int
    {
        return $this->idResponse;
    }

    public function setIdResponse(int $idResponse): self
    {
        $this->idResponse = $idResponse;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: false)]
    private ?string $message = null;

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $dateResponse = null;

    public function getDateResponse(): ?\DateTimeInterface
    {
        return $this->dateResponse;
    }

    public function setDateResponse(\DateTimeInterface $dateResponse): self
    {
        $this->dateResponse = $dateResponse;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $typeResponse = null;

    public function getTypeResponse(): ?string
    {
        return $this->typeResponse;
    }

    public function setTypeResponse(string $typeResponse): self
    {
        $this->typeResponse = $typeResponse;
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

    #[ORM\Column(type: 'integer', nullable: false)]
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

}
