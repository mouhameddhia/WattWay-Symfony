<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Car;
use App\Entity\Response;

#[ORM\Entity]
class User
{
    #[ORM\Id]
    #[ORM\Column(name: "idUser", type: "integer")]
    private int $idUser;

    #[ORM\Column(type: "string", length: 100)]
    private string $emailUser;

    #[ORM\Column(type: "string", length: 255)]
    private string $passwordUser;

    #[ORM\Column(type: "string", length: 50)]
    private string $firstNameUser;

    #[ORM\Column(type: "string", length: 50)]
    private string $lastNameUser;

    #[ORM\Column(type: "string")]  
    private string $roleUser;

    #[ORM\Column(type: "string", length: 20)]
    private string $phoneNumber;

    #[ORM\Column(type: "string")]  
    private string $paymentDetails;

    #[ORM\Column(type: "string", length: 255)]
    private string $address;

    #[ORM\Column(type: "string")]  
    private string $functionAdmin;

    #[ORM\Column(type: "string", length: 255)]
    private string $profilePicture;
    #[ORM\OneToMany(mappedBy: "idUser", targetEntity: Submission::class)]
    private Collection $submissions;

        public function getSubmissions(): Collection
        {
            return $this->submissions;
        }
    
        public function addSubmission(Submission $submission): self
        {
            if (!$this->submissions->contains($submission)) {
                $this->submissions[] = $submission;
                $submission->setUser($this);
            }
    
            return $this;
        }
    
        public function removeSubmission(Submission $submission): self
        {
            if ($this->submissions->removeElement($submission)) {
                // set the owning side to null (unless already changed)
                if ($submission->getUser() === $this) {
                    $submission->setUser(null);
                }
            }
    
            return $this;
        }

    // --- Inverse side of Car relation ---
    #[ORM\OneToMany(mappedBy: "idUser", targetEntity: Car::class)]
    private Collection $cars;

    // --- Inverse side of Response relation ---
    #[ORM\OneToMany(mappedBy: "idUser", targetEntity: Response::class)]
    private Collection $responses;

    public function __construct()
    {
        $this->cars = new ArrayCollection();
        $this->responses = new ArrayCollection();
    }

    // --- Car getters & setters ---
    public function getCars(): Collection
    {
        return $this->cars;
    }

    public function addCar(Car $car): self
    {
        if (!$this->cars->contains($car)) {
            $this->cars->add($car);
            $car->setUser($this);
        }
        return $this;
    }

    public function removeCar(Car $car): self
    {
        if ($this->cars->removeElement($car)) {
            if ($car->getUser() === $this) {
                $car->setUser(null);
            }
        }
        return $this;
    }

    // --- Response getters & setters ---
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    // --- Existing simple getters & setters ---

    public function getIdUser()
    {
        return $this->idUser;
    }

    public function setIdUser($value)
    {
        $this->idUser = $value;
    }

    public function getEmailUser()
    {
        return $this->emailUser;
    }

    public function setEmailUser($value)
    {
        $this->emailUser = $value;
    }

    public function getPasswordUser()
    {
        return $this->passwordUser;
    }

    public function setPasswordUser($value)
    {
        $this->passwordUser = $value;
    }

    public function getFirstNameUser()
    {
        return $this->firstNameUser;
    }

    public function setFirstNameUser($value)
    {
        $this->firstNameUser = $value;
    }

    public function getLastNameUser()
    {
        return $this->lastNameUser;
    }

    public function setLastNameUser($value)
    {
        $this->lastNameUser = $value;
    }

    public function getRoleUser()
    {
        return $this->roleUser;
    }

    public function setRoleUser($value)
    {
        $this->roleUser = $value;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber($value)
    {
        $this->phoneNumber = $value;
    }

    public function getPaymentDetails()
    {
        return $this->paymentDetails;
    }

    public function setPaymentDetails($value)
    {
        $this->paymentDetails = $value;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($value)
    {
        $this->address = $value;
    }

    public function getFunctionAdmin()
    {
        return $this->functionAdmin;
    }

    public function setFunctionAdmin($value)
    {
        $this->functionAdmin = $value;
    }

    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    public function setProfilePicture($value)
    {
        $this->profilePicture = $value;
    }
}