<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user')]
#[UniqueEntity(
    fields: ['emailUser'],
    message: 'This email is already in use.'
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"idUser",type: 'integer')]
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

    #[ORM\Column(name:"emailUser", type: 'string', unique: true, nullable: false)]
    #[Assert\NotBlank(message: "Email cannot be blank.")]
    #[Assert\Email(message: "Please enter a valid email address.")]
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

    #[ORM\Column(name:"passwordUser", type: 'string', nullable: false)]
    #[Assert\Length(
        min: 6,
        minMessage: "Password must be at least {{ limit }} characters long."
    )]
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

    #[ORM\Column(name:"firstNameUser", type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "First name cannot be blank.")]
    #[Groups(['bill:read'])]
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

    #[ORM\Column(name:"lastNameUser", type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "Last name cannot be blank.")]
    #[Groups(['bill:read'])]
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

    #[ORM\Column(name:"roleUser" ,type: 'string', nullable: false)]
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

    #[ORM\Column(name:"phoneNumber", type: 'string', unique: true, nullable: true)]
    #[Assert\NotBlank(message: "Phone number cannot be blank.")]
    #[Assert\Regex(
        pattern: "/^\d{8}$/",
        message: "Phone number must be exactly 8 digits."
    )]
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

    #[ORM\Column(name:"paymentDetails", type: "string",
    nullable: false,
    columnDefinition: "ENUM('PAYPAL', 'CREDIT_CARD', 'BANK_TRANSFER')")]
    #[Assert\NotBlank(message: "Payment details cannot be blank.")]
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

    #[ORM\Column(name:"address", type: 'string', nullable: true)]
    #[Assert\NotBlank(message: "Address cannot be blank.")]
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

    #[ORM\Column(name:"functionAdmin",type: 'string', nullable: true)]
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

    #[ORM\Column(name: "profilePicture", type: 'string', length: 255, nullable: true)]
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

    // UserInterface methods
    public function getUsername(): string
    {
        return $this->emailUser;
    }

    
    public function getRoles(): array
    {
        return [$this->roleUser]; // You can customize this based on your needs
    }
    public function getUserIdentifier(): string
    {
        return $this->emailUser;
    }

    public function eraseCredentials(): void
    {
        // You can clear sensitive data here if needed
    }

    public function getPassword(): string
    {
        return $this->passwordUser;
    }

    public function setPassword(string $password): self
    {
        $this->passwordUser = $password;
        return $this;
    }



    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Bill::class)]
    private Collection $bills;
    public function __construct()
    {
        $this->bills = new ArrayCollection();
    }
    /**
     * @return Collection<int, Bill>
     */
    public function getBills(): Collection
    {
        if (!$this->bills instanceof Collection) {
            $this->bills = new ArrayCollection();
        }
        return $this->bills;
    }
    public function addBill(Bill $bill): self
    {
        if (!$this->bills->contains($bill)) {
            $this->bills[] = $bill;
            $bill->setUser($this);
        }
        return $this;
    }
    public function removeBill(Bill $bill): self
    {
        if ($this->bills->removeElement($bill)) {
            // set the owning side to null (unless already changed)
            if ($bill->getUser() === $this) {
                $bill->setUser(null);
            }
        }
        return $this;
    }
}
