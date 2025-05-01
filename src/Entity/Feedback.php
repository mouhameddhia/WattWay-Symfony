<?php
// src/Entity/Feedback.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FeedbackRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
#[ORM\Table(name: 'feedback')]
class Feedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idFeedback", type: 'integer')]
    private ?int $idFeedback = null;

    #[ORM\Column(name: "contentFeedback", type: 'text')]
    #[Assert\Length(
        min: 50,
        max: 500,
        minMessage: "Feedback must be at least {{ limit }} characters long.",
        maxMessage: "Feedback cannot be longer than {{ limit }} characters."
    )]
    private ?string $content = null;

    #[ORM\Column(name: "ratingFeedback", type: 'integer', nullable: true)]
    private ?int $rating = null;

    #[ORM\Column(name: "dateFeedback", type: 'datetime', nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "idUser", referencedColumnName: "idUser")]
    private ?User $user = null;

    // Getters and Setters
    public function getIdFeedback(): ?int
    {
        return $this->idFeedback;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
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
}
