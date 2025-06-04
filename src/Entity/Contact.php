<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $firstName = null;

    #[ORM\Column(length: 100)]
    private ?string $lastName = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 20)]
    private ?string $phone = null;

    #[ORM\Column(length: 50)]
    private ?string $objectif = null;

    #[ORM\Column(length: 50)]
    private ?string $niveau = null;

    #[ORM\Column(type: 'text')]
    private ?string $source = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getObjectif(): ?string
    {
        return $this->objectif;
    }
    public function setObjectif(string $objectif): self
    {
        $this->objectif = $objectif;
        return $this;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }
    public function setNiveau(string $niveau): self
    {
        $this->niveau = $niveau;
        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }
    public function setSource(string $source): self
    {
        $this->source = $source;
        return $this;
    }
}
