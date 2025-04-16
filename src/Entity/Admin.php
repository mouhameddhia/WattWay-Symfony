<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\AdminRepository;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
#[ORM\Table(name: 'admin')]
class Admin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_admin = null;

    public function getId_admin(): ?int
    {
        return $this->id_admin;
    }

    public function setId_admin(int $id_admin): self
    {
        $this->id_admin = $id_admin;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $function_admin = null;

    public function getFunction_admin(): ?string
    {
        return $this->function_admin;
    }

    public function setFunction_admin(string $function_admin): self
    {
        $this->function_admin = $function_admin;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $name_admin = null;

    public function getName_admin(): ?string
    {
        return $this->name_admin;
    }

    public function setName_admin(string $name_admin): self
    {
        $this->name_admin = $name_admin;
        return $this;
    }

    public function getIdAdmin(): ?int
    {
        return $this->id_admin;
    }

    public function getFunctionAdmin(): ?string
    {
        return $this->function_admin;
    }

    public function setFunctionAdmin(string $function_admin): static
    {
        $this->function_admin = $function_admin;

        return $this;
    }

    public function getNameAdmin(): ?string
    {
        return $this->name_admin;
    }

    public function setNameAdmin(string $name_admin): static
    {
        $this->name_admin = $name_admin;

        return $this;
    }

}
