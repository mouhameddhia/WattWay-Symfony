<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
class Feedback
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $idFeedback;

    #[ORM\Column(type: "text")]
    private string $contentFeedback;

    #[ORM\Column(type: "integer")]
    private int $ratingFeedback;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $dateFeedback;

    #[ORM\Column(type: "integer")]
    private int $idUser;

    public function getIdFeedback()
    {
        return $this->idFeedback;
    }

    public function setIdFeedback($value)
    {
        $this->idFeedback = $value;
    }

    public function getContentFeedback()
    {
        return $this->contentFeedback;
    }

    public function setContentFeedback($value)
    {
        $this->contentFeedback = $value;
    }

    public function getRatingFeedback()
    {
        return $this->ratingFeedback;
    }

    public function setRatingFeedback($value)
    {
        $this->ratingFeedback = $value;
    }

    public function getDateFeedback()
    {
        return $this->dateFeedback;
    }

    public function setDateFeedback($value)
    {
        $this->dateFeedback = $value;
    }

    public function getIdUser()
    {
        return $this->idUser;
    }

    public function setIdUser($value)
    {
        $this->idUser = $value;
    }
}
