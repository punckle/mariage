<?php

namespace App\Entity;

use App\Repository\GuestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $come;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isInvitedApero = true;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isInvitedDinner = false;

    /**
     * @ORM\OneToMany(targetEntity=GuestPlusOne::class, mappedBy="guest")
     */
    private $guestPlusOnes;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $message;

    public function __construct()
    {
        $this->guestPlusOnes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->lastName . ' ' . $this->firstName;
    }

    public function toJson(): array
    {
        $guest = [
            'id' => $this->id,
            'isInvitedApero' => $this->isInvitedApero,
            'isInvitedDinner' => $this->isInvitedDinner,
            'initialNbPeople' => $this->initialNbPeople
        ];

        return $guest;
    }

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

    public function getCome(): ?bool
    {
        return $this->come;
    }

    public function setCome(?bool $come): self
    {
        $this->come = $come;

        return $this;
    }

    public function getIsInvitedApero(): ?bool
    {
        return $this->isInvitedApero;
    }

    public function setIsInvitedApero(bool $isInvitedApero): self
    {
        $this->isInvitedApero = $isInvitedApero;

        return $this;
    }

    public function getIsInvitedDinner(): ?bool
    {
        return $this->isInvitedDinner;
    }

    public function setIsInvitedDinner(bool $isInvitedDinner): self
    {
        $this->isInvitedDinner = $isInvitedDinner;

        return $this;
    }

    /**
     * @return Collection|GuestPlusOne[]
     */
    public function getGuestPlusOnes(): Collection
    {
        return $this->guestPlusOnes;
    }

    public function addGuestPlusOne(GuestPlusOne $guestPlusOne): self
    {
        if (!$this->guestPlusOnes->contains($guestPlusOne)) {
            $this->guestPlusOnes[] = $guestPlusOne;
            $guestPlusOne->setGuest($this);
        }

        return $this;
    }

    public function removeGuestPlusOne(GuestPlusOne $guestPlusOne): self
    {
        if ($this->guestPlusOnes->removeElement($guestPlusOne)) {
            // set the owning side to null (unless already changed)
            if ($guestPlusOne->getGuest() === $this) {
                $guestPlusOne->setGuest(null);
            }
        }

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
