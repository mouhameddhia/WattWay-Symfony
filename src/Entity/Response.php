<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\ResponseRepository;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ResponseRepository::class)]
#[ORM\Table(name: 'response')]
class Response
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'idResponse',type: 'integer')]
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

    #[ORM\Column(name:'message',type: 'text', nullable: false)]
    #[Assert\NotBlank(message: 'Message should not be blank.')]
    #[Assert\Length(min: 5, max: 200)]
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

    #[ORM\Column(name:'dateResponse',type: 'date', nullable: false)]
    #[Assert\NotNull(message: 'Date should not be null.')]
    #[Assert\EqualTo('today')]
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

    #[ORM\Column(name:'typeResponse',type: 'string', nullable: false)]
    #[Assert\NotBlank(message: 'Type should not be blank.')]
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

    #[ORM\Column(name:'idUser',type: 'integer', nullable: false)]
    #[Assert\NotNull(message: 'User ID should not be null.')]
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

    #[ORM\Column(name:'idSubmission',type: 'integer', nullable: false)]
    #[Assert\NotNull(message: 'Submission ID should not be null.')]
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
