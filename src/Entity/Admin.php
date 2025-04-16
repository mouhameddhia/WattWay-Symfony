<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
class Admin
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_admin;

    #[ORM\Column(type: "string", length: 100)]
    private string $function_admin;

    #[ORM\Column(type: "string", length: 255)]
    private string $name_admin;

    public function getId_admin()
    {
        return $this->id_admin;
    }

    public function setId_admin($value)
    {
        $this->id_admin = $value;
    }

    public function getFunction_admin()
    {
        return $this->function_admin;
    }

    public function setFunction_admin($value)
    {
        $this->function_admin = $value;
    }

    public function getName_admin()
    {
        return $this->name_admin;
    }

    public function setName_admin($value)
    {
        $this->name_admin = $value;
    }
}
