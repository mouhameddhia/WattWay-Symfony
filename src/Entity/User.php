<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
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

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $emailUser = null;

    public function getEmailUser(): ?string
    {
        return $this->emailUser;
    }

    public function setEmailUser(string $emailUser): self
    {
        $this->emailUser = $emailUser;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $passwordUser = null;

    public function getPasswordUser(): ?string
    {
        return $this->passwordUser;
    }

    public function setPasswordUser(string $passwordUser): self
    {
        $this->passwordUser = $passwordUser;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $firstNameUser = null;

    public function getFirstNameUser(): ?string
    {
        return $this->firstNameUser;
    }

    public function setFirstNameUser(string $firstNameUser): self
    {
        $this->firstNameUser = $firstNameUser;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $lastNameUser = null;

    public function getLastNameUser(): ?string
    {
        return $this->lastNameUser;
    }

    public function setLastNameUser(string $lastNameUser): self
    {
        $this->lastNameUser = $lastNameUser;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $roleUser = null;

    public function getRoleUser(): ?string
    {
        return $this->roleUser;
    }

    public function setRoleUser(string $roleUser): self
    {
        $this->roleUser = $roleUser;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $phoneNumber = null;

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $paymentDetails = null;

    public function getPaymentDetails(): ?string
    {
        return $this->paymentDetails;
    }

    public function setPaymentDetails(string $paymentDetails): self
    {
        $this->paymentDetails = $paymentDetails;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $address = null;

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $functionAdmin = null;

    public function getFunctionAdmin(): ?string
    {
        return $this->functionAdmin;
    }

    public function setFunctionAdmin(?string $functionAdmin): self
    {
        $this->functionAdmin = $functionAdmin;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $profilePicture = null;

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture): self
    {
        $this->profilePicture = $profilePicture;
        return $this;
    }

}
