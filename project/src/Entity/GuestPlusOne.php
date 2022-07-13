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
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var ?string
     */
    private $lastName;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $apero = false;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $dinner = false;

    /**
     * @ORM\ManyToOne(targetEntity=Guest::class, inversedBy="guestPlusOnes")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Guest $guest;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @var ?string
     */
    private $comment;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $kid = false;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $ceremony = false;

    public function __toString()
    {
        return $this->lastName . ' ' . $this->firstName;
    }

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

    public function getCeremony(): bool
    {
        return $this->ceremony;
    }

    public function setCeremony(bool $ceremony): self
    {
        $this->ceremony = $ceremony;

        return $this;
    }
}
