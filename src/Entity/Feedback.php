<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\FeedbackRepository;

#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
#[ORM\Table(name: 'feedback')]
class Feedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $idFeedback = null;

    public function getIdFeedback(): ?int
    {
        return $this->idFeedback;
    }

    public function setIdFeedback(int $idFeedback): self
    {
        $this->idFeedback = $idFeedback;
        return $this;
    }

    #[ORM\Column(type: 'text')]
    private ?string $contentFeedback = null;

    public function getContentFeedback(): ?string
    {
        return $this->contentFeedback;
    }

    public function setContentFeedback(string $contentFeedback): self
    {
        $this->contentFeedback = $contentFeedback;
        return $this;
    }

    #[ORM\Column(type: 'integer')]
    private ?int $ratingFeedback = null;

    public function getRatingFeedback(): ?int
    {
        return $this->ratingFeedback;
    }

    public function setRatingFeedback(?int $ratingFeedback): self
    {
        $this->ratingFeedback = $ratingFeedback;
        return $this;
    }

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateFeedback = null;

    public function getDateFeedback(): ?\DateTimeInterface
    {
        return $this->dateFeedback;
    }

    public function setDateFeedback(?\DateTimeInterface $dateFeedback): self
    {
        $this->dateFeedback = $dateFeedback;
        return $this;
    }

    #[ORM\Column(type: 'integer')]
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

}
