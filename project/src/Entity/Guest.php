<?php

namespace App\Entity;

use App\Repository\GuestRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=GuestRepository::class)
 * @UniqueEntity("code")
 */
class Guest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="integer")
     */
    private $initialNbPeople;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $finalNbPeople;

    /**
     * @ORM\Column(type="boolean")
     */
    private $codeActif;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
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

    public function getInitialNbPeople(): ?int
    {
        return $this->initialNbPeople;
    }

    public function setInitialNbPeople(int $initialNbPeople): self
    {
        $this->initialNbPeople = $initialNbPeople;

        return $this;
    }

    public function getFinalNbPeople(): ?int
    {
        return $this->finalNbPeople;
    }

    public function setFinalNbPeople(?int $finalNbPeople): self
    {
        $this->finalNbPeople = $finalNbPeople;

        return $this;
    }

    public function getCodeActif(): ?bool
    {
        return $this->codeActif;
    }

    public function setCodeActif(bool $codeActif): self
    {
        $this->codeActif = $codeActif;

        return $this;
    }
}
