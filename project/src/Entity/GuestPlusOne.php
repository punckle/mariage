<?php

namespace App\Entity;

use App\Repository\GuestPlusOneRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GuestPlusOneRepository::class)
 */
class GuestPlusOne
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $apero = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $dinner = false;

    /**
     * @ORM\ManyToOne(targetEntity=Guest::class, inversedBy="guestPlusOnes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $guest;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="boolean")
     */
    private $kid = false;

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

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getApero(): ?bool
    {
        return $this->apero;
    }

    public function setApero(bool $apero): self
    {
        $this->apero = $apero;

        return $this;
    }

    public function getDinner(): ?bool
    {
        return $this->dinner;
    }

    public function setDinner(bool $dinner): self
    {
        $this->dinner = $dinner;

        return $this;
    }

    public function getGuest(): ?Guest
    {
        return $this->guest;
    }

    public function setGuest(?Guest $guest): self
    {
        $this->guest = $guest;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getKid(): ?bool
    {
        return $this->kid;
    }

    public function setKid(bool $kid): self
    {
        $this->kid = $kid;

        return $this;
    }
}
